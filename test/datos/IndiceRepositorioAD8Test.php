<?php

declare(strict_types=1);

/**
 * Test de integración: AD8 — Producción de materiales pedagógicos
 * (docs/indice/mapeo-origen.md, sección AD8). Sin mocks, contra una ficha
 * real (de prueba) en la base `desempenio`.
 *
 * Mismo bug que AD4/AD5/AD6/AD7: las tablas de valoración (material y tarea)
 * estaban keyed por texto, y a la de material le faltaban dos tipos
 * ('Desarrollo de apps', 'Publicaciones'). Corregido en
 * sql/indice/11_ad678_fix_valoracion_por_id.sql.
 *
 * El puntaje del ítem es multiplicativo (valor del material x valor de la
 * tarea, docs/indice/modelo-de-calculo.md #4), y el umbral de AD8 es el caso
 * particular de docs/indice/decisiones-pendientes.md A3: minimo=superior=19
 * (sembrado así para que la suma de mínimos reproduzca el UBD de la
 * planilla), así que el tramo [minimo, superior] de valoración=1 es un único
 * punto exacto.
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceRepositorioAD8Test extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000012;
    private const DNI = 90000012;
    private const ANIO = 2025;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_items_materiales_pedagogicos_traduce_los_campos(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearMaterial($ficha_id, mat_tipo_id: 4, tipo_tarea_id: 1); // Desarrollo de apps (el que faltaba), Desarrollo
        $this->crearMaterial($ficha_id, mat_tipo_id: 5, tipo_tarea_id: 2); // Publicaciones (el que faltaba), Actualización

        $items = indice_repositorio::items_materiales_pedagogicos(self::LEGAJO, self::ANIO);

        self::assertCount(2, $items);
        self::assertSame('4', $items[0]->valor('material'));
        self::assertSame('1', $items[0]->valor('tarea'));
        self::assertSame('5', $items[1]->valor('material'));
        self::assertSame('2', $items[1]->valor('tarea'));
        self::assertSame('c38_materiales_pedagogicos', $items[0]->origenTabla);
    }

    public function test_componentes_de_ad8_reproduce_las_reglas_sembradas(): void
    {
        $componentes = indice_repositorio::componentes('AD8', self::ANIO);

        self::assertCount(2, $componentes);
        foreach ($componentes as $componente) {
            self::assertSame(1, $componente->termino); // multiplicativo: material x tarea
        }

        self::assertSame('material', $componentes[0]->campoClave);
        self::assertSame(
            [
                '1' => '8.000000', // Apunte de clases
                '2' => '5.000000', // Trabajo práctico
                '3' => '8.000000', // Entorno virtual
                '4' => '1.000000', // Desarrollo de apps
                '5' => '1.000000', // Publicaciones
            ],
            $componentes[0]->tablaValoracion,
        );

        self::assertSame('tarea', $componentes[1]->campoClave);
        self::assertSame(['1' => '5.000000', '2' => '1.000000'], $componentes[1]->tablaValoracion);
    }

    public function test_umbral_de_ad8_jtp_ds_minimo_igual_superior(): void
    {
        // decisiones-pendientes.md A3: sembrado con minimo=superior=19.
        self::assertSame(
            ['minimo' => '19.000000', 'superior' => '19.000000'],
            indice_repositorio::umbral('AD8', 'JTP', 'DS', self::ANIO),
        );
    }

    /**
     * Punta a punta: item1 (Desarrollo de apps=1 x Desarrollo=5) = 5, item2
     * (Entorno virtual=8 x Actualización=1) = 8. PD8 = 13, por debajo del
     * mínimo (19) de JTP/DS -> AD8 = 0.
     */
    public function test_calculo_real_de_ad8_de_punta_a_punta(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearMaterial($ficha_id, mat_tipo_id: 4, tipo_tarea_id: 1); // 1 x 5 = 5
        $this->crearMaterial($ficha_id, mat_tipo_id: 3, tipo_tarea_id: 2); // 8 x 1 = 8

        $items = indice_repositorio::items_materiales_pedagogicos(self::LEGAJO, self::ANIO);
        $componentes = indice_repositorio::componentes('AD8', self::ANIO);
        $umbral = indice_repositorio::umbral('AD8', 'JTP', 'DS', self::ANIO);

        $evaluador = new \Pruebas\Indice\Motor\EvaluadorComponentes();
        $puntaje = '0';
        foreach ($items as $item) {
            $puntaje = bcadd($puntaje, $evaluador->evaluarItem($componentes, $item), 6);
        }

        self::assertSame('13.000000', $puntaje);
        self::assertSame(
            0,
            (new \Pruebas\Indice\Motor\Categorizador())->categorizar($puntaje, $umbral['minimo'], $umbral['superior']),
        );
    }

    private function crearFicha(): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_ad8', :anio, 4, 3)
             RETURNING id",
            ['dni' => self::DNI, 'legajo' => self::LEGAJO, 'anio' => self::ANIO],
        );
        return (int) $fila[0]['id'];
    }

    private function crearMaterial(int $ficha_id, int $mat_tipo_id, int $tipo_tarea_id): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.c38_materiales_pedagogicos (ficha_id, mat_tipo_id, tipo_tarea_id)
             VALUES (:ficha_id, :mat_tipo_id, :tipo_tarea_id)',
            ['ficha_id' => $ficha_id, 'mat_tipo_id' => $mat_tipo_id, 'tipo_tarea_id' => $tipo_tarea_id],
        );
    }

    private function limpiar(): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.c38_materiales_pedagogicos
              WHERE ficha_id IN (SELECT id FROM public.ficha WHERE legajo_doc = :legajo)',
            ['legajo' => self::LEGAJO],
        );
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.ficha WHERE legajo_doc = :legajo',
            ['legajo' => self::LEGAJO],
        );
    }
}
