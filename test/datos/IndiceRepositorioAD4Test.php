<?php

declare(strict_types=1);

/**
 * Test de integración: AD4 — Docencia de posgrado (docs/indice/mapeo-origen.md,
 * sección AD4). Sin mocks, contra una ficha real (de prueba) en la base
 * `desempenio`.
 *
 * Dos puntos confirmados por el usuario (sesión 2026-08-28), no inferidos:
 *  - El lookup del componente decía 'carrera' pero es tipo_posgrado, mal
 *    etiquetado al sembrar (sql/indice/09_ad4_fix_tipo_posgrado.sql).
 *  - 'participacion' no existe como porcentaje en los datos reales — sólo
 *    un rol (public.c34_participacion_tipos): Coordinador=100%, Docente=80%,
 *    Ayudante=20%. Un participacion_id fuera de ese catálogo cuenta 0%, no
 *    lanza excepción (11 filas reales así, valores sueltos que parecen el
 *    default de la secuencia de la columna, nunca un rol cargado).
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceRepositorioAD4Test extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000008;
    private const DNI = 90000008;
    private const ANIO = 2025;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_items_docencia_posgrado_traduce_los_campos(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearPosgrado($ficha_id, tipo_posgrado_id: 2, participacion_id: 2, creditos: 2); // Docente, 80%
        $this->crearPosgrado($ficha_id, tipo_posgrado_id: 6, participacion_id: 3, creditos: 1); // Ayudante, 20%, Otros
        $this->crearPosgrado($ficha_id, tipo_posgrado_id: 1, participacion_id: 99, creditos: 5); // participacion_id huérfano -> 0%

        $items = indice_repositorio::items_docencia_posgrado(self::LEGAJO, self::ANIO);

        self::assertCount(3, $items);

        self::assertSame('2', $items[0]->valor('creditos'));
        self::assertSame('80', $items[0]->valor('participacion'));
        self::assertSame('2', $items[0]->valor('tipo_posgrado'));

        self::assertSame('20', $items[1]->valor('participacion'));
        self::assertSame('6', $items[1]->valor('tipo_posgrado'));

        // participacion_id fuera de catálogo -> 0%, no excepción
        self::assertSame('0', $items[2]->valor('participacion'));

        self::assertSame('c34_docec_posgrado', $items[0]->origenTabla);
    }

    public function test_componentes_de_ad4_reproduce_las_reglas_sembradas(): void
    {
        $componentes = indice_repositorio::componentes('AD4', self::ANIO);

        self::assertCount(3, $componentes);
        foreach ($componentes as $componente) {
            self::assertSame(1, $componente->termino); // multiplicativos
        }

        self::assertSame('directo', $componentes[0]->tipo);
        self::assertSame('creditos', $componentes[0]->campo);

        self::assertSame('proporcion', $componentes[1]->tipo);
        self::assertSame('participacion', $componentes[1]->campo);
        self::assertSame('100.000000', $componentes[1]->divisor);

        self::assertSame('lookup', $componentes[2]->tipo);
        // campo_clave 'tipo_posgrado', no 'carrera' (fix 09_ad4_fix_tipo_posgrado.sql)
        self::assertSame('tipo_posgrado', $componentes[2]->campoClave);
        self::assertSame(
            [
                '1' => '7.000000', // Doctorado
                '2' => '5.000000', // Maestría
                '3' => '3.000000', // Especialización
                '4' => '1.000000', // Diplomatura
                '5' => '0.500000', // Diplomado
                '6' => '1.000000', // Otros
            ],
            $componentes[2]->tablaValoracion,
        );
    }

    public function test_umbral_de_ad4_jtp_ds(): void
    {
        self::assertSame(
            ['minimo' => '1.000000', 'superior' => '2.000000'],
            indice_repositorio::umbral('AD4', 'JTP', 'DS', self::ANIO),
        );
    }

    /**
     * Punta a punta: item1 (Maestría, Docente 80%, 2 créditos) = 2*0.8*5=8;
     * item2 (Otros, Ayudante 20%, 1 crédito) = 1*0.2*1=0.2; item3
     * (participacion_id huérfano -> 0%) = 0. PD4 = 8.2, supera el superior
     * (2) de JTP/DS -> AD4 = 3.
     */
    public function test_calculo_real_de_ad4_de_punta_a_punta(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearPosgrado($ficha_id, tipo_posgrado_id: 2, participacion_id: 2, creditos: 2);
        $this->crearPosgrado($ficha_id, tipo_posgrado_id: 6, participacion_id: 3, creditos: 1);
        $this->crearPosgrado($ficha_id, tipo_posgrado_id: 1, participacion_id: 99, creditos: 5);

        $items = indice_repositorio::items_docencia_posgrado(self::LEGAJO, self::ANIO);
        $componentes = indice_repositorio::componentes('AD4', self::ANIO);
        $umbral = indice_repositorio::umbral('AD4', 'JTP', 'DS', self::ANIO);

        $evaluador = new \Pruebas\Indice\Motor\EvaluadorComponentes();
        $puntaje = '0';
        foreach ($items as $item) {
            $puntaje = bcadd($puntaje, $evaluador->evaluarItem($componentes, $item), 6);
        }

        self::assertSame('8.200000', $puntaje);
        self::assertSame(
            3,
            (new \Pruebas\Indice\Motor\Categorizador())->categorizar($puntaje, $umbral['minimo'], $umbral['superior']),
        );
    }

    private function crearFicha(): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_ad4', :anio, 4, 3)
             RETURNING id",
            ['dni' => self::DNI, 'legajo' => self::LEGAJO, 'anio' => self::ANIO],
        );
        return (int) $fila[0]['id'];
    }

    private function crearPosgrado(int $ficha_id, int $tipo_posgrado_id, int $participacion_id, int $creditos): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.c34_docec_posgrado (ficha_id, tipo_posgrado_id, participacion_id, creditos)
             VALUES (:ficha_id, :tipo, :participacion, :creditos)',
            [
                'ficha_id' => $ficha_id,
                'tipo' => $tipo_posgrado_id,
                'participacion' => $participacion_id,
                'creditos' => $creditos,
            ],
        );
    }

    private function limpiar(): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.c34_docec_posgrado
              WHERE ficha_id IN (SELECT id FROM public.ficha WHERE legajo_doc = :legajo)',
            ['legajo' => self::LEGAJO],
        );
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.ficha WHERE legajo_doc = :legajo',
            ['legajo' => self::LEGAJO],
        );
    }
}
