<?php

declare(strict_types=1);

/**
 * Test de integración: AD5 — Participación en reuniones científicas de
 * docencia (docs/indice/mapeo-origen.md, sección AD5). Sin mocks, contra
 * una ficha real (de prueba) en la base `desempenio`.
 *
 * Mismo bug que AD4 (sql/indice/09_ad4_fix_tipo_posgrado.sql): las tablas de
 * valoración estaban keyed por texto en vez de id. Corregido en
 * sql/indice/10_ad5_fix_valoracion_por_id.sql.
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceRepositorioAD5Test extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000009;
    private const DNI = 90000009;
    private const ANIO = 2025;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_items_reuniones_docencia_traduce_los_campos(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearReunion($ficha_id, tipo_participacion_id: 4, present_tipo_id: 1, referato: false, titulo: 'Evaluador, resumen sin referato');
        $this->crearReunion($ficha_id, tipo_participacion_id: 2, present_tipo_id: 3, referato: true, titulo: 'Expositor, trabajo completo con referato');

        $items = indice_repositorio::items_reuniones_docencia(self::LEGAJO, self::ANIO);

        self::assertCount(2, $items);

        self::assertSame('4', $items[0]->valor('tipo_participacion'));
        self::assertSame('1', $items[0]->valor('tipo_presentacion'));
        self::assertSame('0', $items[0]->valor('referato'));

        self::assertSame('2', $items[1]->valor('tipo_participacion'));
        self::assertSame('3', $items[1]->valor('tipo_presentacion'));
        self::assertSame('1', $items[1]->valor('referato'));

        self::assertSame('c35_reu_cientificas', $items[0]->origenTabla);
        self::assertSame('Evaluador, resumen sin referato', $items[0]->descripcion);
    }

    public function test_componentes_de_ad5_reproduce_las_reglas_sembradas(): void
    {
        $componentes = indice_repositorio::componentes('AD5', self::ANIO);

        self::assertCount(2, $componentes);

        $participacion = $componentes[0];
        self::assertSame(1, $participacion->termino); // aditivo: term1 + term2
        self::assertSame('lookup', $participacion->tipo);
        self::assertSame('tipo_participacion', $participacion->campoClave);
        self::assertNull($participacion->campoClave2);
        self::assertSame(
            ['1' => '1.000000', '2' => '3.000000', '3' => '4.000000', '4' => '5.000000'],
            $participacion->tablaValoracion,
        );

        $presentacion = $componentes[1];
        self::assertSame(2, $presentacion->termino);
        self::assertSame('lookup', $presentacion->tipo);
        self::assertSame('tipo_presentacion', $presentacion->campoClave);
        self::assertSame('referato', $presentacion->campoClave2);
        self::assertSame(
            [
                '1' => ['0' => '1.000000', '1' => '3.000000'], // Resumen
                '2' => ['0' => '3.000000', '1' => '5.000000'], // Trabajo extendido
                '3' => ['0' => '5.000000', '1' => '8.000000'], // Trabajo Completo
            ],
            $presentacion->tablaValoracion,
        );
    }

    public function test_umbral_de_ad5_jtp_ds(): void
    {
        self::assertSame(
            ['minimo' => '1.000000', 'superior' => '5.000000'],
            indice_repositorio::umbral('AD5', 'JTP', 'DS', self::ANIO),
        );
    }

    /**
     * Punta a punta: item1 (Evaluador=5 + Resumen sin referato=1) = 6; item2
     * (Expositor=3 + Trabajo Completo con referato=8) = 11. PD5 = 17, supera
     * el superior (5) de JTP/DS -> AD5 = 3.
     */
    public function test_calculo_real_de_ad5_de_punta_a_punta(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearReunion($ficha_id, tipo_participacion_id: 4, present_tipo_id: 1, referato: false, titulo: 'Item 1');
        $this->crearReunion($ficha_id, tipo_participacion_id: 2, present_tipo_id: 3, referato: true, titulo: 'Item 2');

        $items = indice_repositorio::items_reuniones_docencia(self::LEGAJO, self::ANIO);
        $componentes = indice_repositorio::componentes('AD5', self::ANIO);
        $umbral = indice_repositorio::umbral('AD5', 'JTP', 'DS', self::ANIO);

        $evaluador = new \Pruebas\Indice\Motor\EvaluadorComponentes();
        $puntaje = '0';
        foreach ($items as $item) {
            $puntaje = bcadd($puntaje, $evaluador->evaluarItem($componentes, $item), 6);
        }

        self::assertSame('17.000000', $puntaje);
        self::assertSame(
            3,
            (new \Pruebas\Indice\Motor\Categorizador())->categorizar($puntaje, $umbral['minimo'], $umbral['superior']),
        );
    }

    private function crearFicha(): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_ad5', :anio, 4, 3)
             RETURNING id",
            ['dni' => self::DNI, 'legajo' => self::LEGAJO, 'anio' => self::ANIO],
        );
        return (int) $fila[0]['id'];
    }

    private function crearReunion(int $ficha_id, int $tipo_participacion_id, int $present_tipo_id, bool $referato, string $titulo): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.c35_reu_cientificas (ficha_id, tipo_participacion_id, present_tipo_id, referato, titulo)
             VALUES (:ficha_id, :tipo_participacion, :present_tipo, :referato, :titulo)',
            [
                'ficha_id' => $ficha_id,
                'tipo_participacion' => $tipo_participacion_id,
                'present_tipo' => $present_tipo_id,
                'referato' => $referato ? 't' : 'f',
                'titulo' => $titulo,
            ],
        );
    }

    private function limpiar(): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.c35_reu_cientificas
              WHERE ficha_id IN (SELECT id FROM public.ficha WHERE legajo_doc = :legajo)',
            ['legajo' => self::LEGAJO],
        );
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.ficha WHERE legajo_doc = :legajo',
            ['legajo' => self::LEGAJO],
        );
    }
}
