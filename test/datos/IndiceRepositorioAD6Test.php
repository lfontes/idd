<?php

declare(strict_types=1);

/**
 * Test de integración: AD6 — Proyectos en docencia acreditados
 * (docs/indice/mapeo-origen.md, sección AD6). Sin mocks, contra una ficha
 * real (de prueba) en la base `desempenio`.
 *
 * Mismo bug que AD4/AD5: la tabla de valoración estaba keyed por texto.
 * Corregido en sql/indice/11_ad678_fix_valoracion_por_id.sql.
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceRepositorioAD6Test extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000010;
    private const DNI = 90000010;
    private const ANIO = 2025;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_items_proyectos_docencia_traduce_el_campo(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearProyecto($ficha_id, tipo_participacion_id: 1, nombre: 'Director de proyecto');
        $this->crearProyecto($ficha_id, tipo_participacion_id: 4, nombre: 'Evaluador de proyecto');

        $items = indice_repositorio::items_proyectos_docencia(self::LEGAJO, self::ANIO);

        self::assertCount(2, $items);
        self::assertSame('1', $items[0]->valor('tipo_participacion'));
        self::assertSame('4', $items[1]->valor('tipo_participacion'));
        self::assertSame('c36_proyectos_educativos', $items[0]->origenTabla);
        self::assertSame('Director de proyecto', $items[0]->descripcion);
    }

    public function test_componentes_de_ad6_reproduce_las_reglas_sembradas(): void
    {
        $componentes = indice_repositorio::componentes('AD6', self::ANIO);

        self::assertCount(1, $componentes);
        self::assertSame('lookup', $componentes[0]->tipo);
        self::assertSame('tipo_participacion', $componentes[0]->campoClave);
        self::assertSame(
            ['1' => '8.000000', '2' => '5.000000', '3' => '1.000000', '4' => '5.000000'],
            $componentes[0]->tablaValoracion,
        );
    }

    public function test_umbral_de_ad6_jtp_ds(): void
    {
        self::assertSame(
            ['minimo' => '1.000000', 'superior' => '8.000000'],
            indice_repositorio::umbral('AD6', 'JTP', 'DS', self::ANIO),
        );
    }

    /**
     * Punta a punta: Director (8) + Evaluador (5) = 13, supera el superior
     * (8) de JTP/DS -> AD6 = 3.
     */
    public function test_calculo_real_de_ad6_de_punta_a_punta(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearProyecto($ficha_id, tipo_participacion_id: 1, nombre: 'Item 1');
        $this->crearProyecto($ficha_id, tipo_participacion_id: 4, nombre: 'Item 2');

        $items = indice_repositorio::items_proyectos_docencia(self::LEGAJO, self::ANIO);
        $componentes = indice_repositorio::componentes('AD6', self::ANIO);
        $umbral = indice_repositorio::umbral('AD6', 'JTP', 'DS', self::ANIO);

        $evaluador = new \Pruebas\Indice\Motor\EvaluadorComponentes();
        $puntaje = '0';
        foreach ($items as $item) {
            $puntaje = bcadd($puntaje, $evaluador->evaluarItem($componentes, $item), 6);
        }

        self::assertSame('13.000000', $puntaje);
        self::assertSame(
            3,
            (new \Pruebas\Indice\Motor\Categorizador())->categorizar($puntaje, $umbral['minimo'], $umbral['superior']),
        );
    }

    private function crearFicha(): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_ad6', :anio, 4, 3)
             RETURNING id",
            ['dni' => self::DNI, 'legajo' => self::LEGAJO, 'anio' => self::ANIO],
        );
        return (int) $fila[0]['id'];
    }

    private function crearProyecto(int $ficha_id, int $tipo_participacion_id, string $nombre): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.c36_proyectos_educativos (ficha_id, tipo_participacion_id, nombre_proyecto)
             VALUES (:ficha_id, :tipo, :nombre)',
            ['ficha_id' => $ficha_id, 'tipo' => $tipo_participacion_id, 'nombre' => $nombre],
        );
    }

    private function limpiar(): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.c36_proyectos_educativos
              WHERE ficha_id IN (SELECT id FROM public.ficha WHERE legajo_doc = :legajo)',
            ['legajo' => self::LEGAJO],
        );
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.ficha WHERE legajo_doc = :legajo',
            ['legajo' => self::LEGAJO],
        );
    }
}
