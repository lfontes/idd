<?php

declare(strict_types=1);

/**
 * Test de integración: indice_orquestador::guardarAD1() contra la base real
 * — verifica que persiste evaluacion/evaluacion_dimension/evaluacion_item,
 * que recalcular un año no duplica filas, y que un segundo año extiende la
 * misma evaluación (anio_hasta), sin construir evaluacion_periodo/actividad/
 * indice (período de k años, pendiente).
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceOrquestadorGuardarAD1Test extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000004;
    private const DNI = 90000004;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_guarda_evaluacion_dimension_e_item(): void
    {
        $ficha_id = $this->crearFicha(2025, categoria_id: 4, dedicacion_id: 3); // JTP/DS
        $this->crearFormacionAcademica($ficha_id, 2, 'Ingeniero Agrónomo'); // Grado, w=3
        $this->crearFormacionAcademica($ficha_id, 4, 'Doctor en Agronomía'); // Doctorado, w=5

        $resultado = indice_orquestador::guardarAD1(self::LEGAJO, 2025);

        self::assertNotNull($resultado);
        self::assertSame('8.000000', $resultado->puntaje);
        self::assertSame(3, $resultado->valoracion);

        $evaluacion = toba::db('desempenio')->consultar_sentencia(
            'SELECT docente_id, anio_desde, anio_hasta, estado FROM indice.evaluacion WHERE docente_id = :d',
            ['d' => self::LEGAJO],
        );
        self::assertCount(1, $evaluacion);
        self::assertSame((string) self::LEGAJO, (string) $evaluacion[0]['docente_id']);
        self::assertSame(2025, (int) $evaluacion[0]['anio_desde']);
        self::assertSame(2025, (int) $evaluacion[0]['anio_hasta']);
        self::assertSame('provisorio', $evaluacion[0]['estado']);

        $dimension = toba::db('desempenio')->consultar_sentencia(
            "SELECT ed.puntaje, ed.valoracion, ed.minimo, ed.superior
               FROM indice.evaluacion_dimension ed
               JOIN indice.evaluacion e ON e.id = ed.evaluacion_id
              WHERE e.docente_id = :d AND ed.anio = 2025",
            ['d' => self::LEGAJO],
        );
        self::assertCount(1, $dimension);
        self::assertSame('8.000000', $dimension[0]['puntaje']);
        self::assertSame(3, (int) $dimension[0]['valoracion']);

        $items = toba::db('desempenio')->consultar_sentencia(
            "SELECT descripcion, puntaje, origen_tabla, origen_id
               FROM indice.evaluacion_item ei
               JOIN indice.evaluacion e ON e.id = ei.evaluacion_id
              WHERE e.docente_id = :d AND ei.anio = 2025
              ORDER BY origen_id",
            ['d' => self::LEGAJO],
        );
        self::assertCount(2, $items);
        self::assertSame('3.000000', $items[0]['puntaje']);
        self::assertSame('5.000000', $items[1]['puntaje']);
        self::assertSame('formacion_academica', $items[0]['origen_tabla']);
    }

    /**
     * evaluacion_item.descripcion es varchar(300). Encontrado corriendo
     * indice_orquestador::calcularTodosLosDocentes() contra la base real
     * (2026-08-28): 12 legajos tenían un texto real que superaba el límite y
     * hacía fallar el INSERT (value too long). Se trunca en la persistencia
     * (indice_repositorio::truncar_descripcion()), no en el motor.
     */
    public function test_descripcion_muy_larga_se_trunca_en_vez_de_fallar(): void
    {
        $ficha_id = $this->crearFicha(2025, categoria_id: 4, dedicacion_id: 3);
        $tituloLargo = str_repeat('a', 350);
        $this->crearFormacionAcademica($ficha_id, 2, $tituloLargo);

        indice_orquestador::guardarAD1(self::LEGAJO, 2025);

        $items = toba::db('desempenio')->consultar_sentencia(
            "SELECT descripcion FROM indice.evaluacion_item ei
               JOIN indice.evaluacion e ON e.id = ei.evaluacion_id
              WHERE e.docente_id = :d AND ei.anio = 2025",
            ['d' => self::LEGAJO],
        );

        self::assertCount(1, $items);
        self::assertSame(300, strlen($items[0]['descripcion']));
        self::assertStringEndsWith('...', $items[0]['descripcion']);
    }

    public function test_recalcular_el_mismo_anio_no_duplica_filas(): void
    {
        $ficha_id = $this->crearFicha(2025, categoria_id: 4, dedicacion_id: 3);
        $this->crearFormacionAcademica($ficha_id, 2, 'Ingeniero Agrónomo');

        indice_orquestador::guardarAD1(self::LEGAJO, 2025);
        indice_orquestador::guardarAD1(self::LEGAJO, 2025); // recalcula el mismo año

        $evaluaciones = toba::db('desempenio')->consultar_sentencia(
            'SELECT count(*) c FROM indice.evaluacion WHERE docente_id = :d',
            ['d' => self::LEGAJO],
        );
        self::assertSame(1, (int) $evaluaciones[0]['c']);

        $items = toba::db('desempenio')->consultar_sentencia(
            "SELECT count(*) c FROM indice.evaluacion_item ei
               JOIN indice.evaluacion e ON e.id = ei.evaluacion_id
              WHERE e.docente_id = :d AND ei.anio = 2025",
            ['d' => self::LEGAJO],
        );
        self::assertSame(1, (int) $items[0]['c']);
    }

    public function test_segundo_anio_extiende_la_misma_evaluacion(): void
    {
        // La normativa sembrada (IDD-2025) cubre desde 2025-01-01: se usan
        // 2025/2026, no 2024, para no depender de una normativa anterior.
        $f1 = $this->crearFicha(2025, categoria_id: 4, dedicacion_id: 3);
        $this->crearFormacionAcademica($f1, 2, 'Ingeniero Agrónomo');
        $f2 = $this->crearFicha(2026, categoria_id: 4, dedicacion_id: 3);
        $this->crearFormacionAcademica($f2, 4, 'Doctor en Agronomía');

        indice_orquestador::guardarAD1(self::LEGAJO, 2025);
        indice_orquestador::guardarAD1(self::LEGAJO, 2026);

        $evaluacion = toba::db('desempenio')->consultar_sentencia(
            'SELECT anio_desde, anio_hasta FROM indice.evaluacion WHERE docente_id = :d',
            ['d' => self::LEGAJO],
        );
        self::assertCount(1, $evaluacion); // una sola evaluación "provisorio" para ambos años
        self::assertSame(2025, (int) $evaluacion[0]['anio_desde']);
        self::assertSame(2026, (int) $evaluacion[0]['anio_hasta']);

        $dimensiones = toba::db('desempenio')->consultar_sentencia(
            "SELECT ed.anio, ed.puntaje FROM indice.evaluacion_dimension ed
               JOIN indice.evaluacion e ON e.id = ed.evaluacion_id
              WHERE e.docente_id = :d ORDER BY ed.anio",
            ['d' => self::LEGAJO],
        );
        self::assertCount(2, $dimensiones);
        self::assertSame(2025, (int) $dimensiones[0]['anio']);
        self::assertSame(2026, (int) $dimensiones[1]['anio']);
    }

    public function test_docente_sin_ficha_no_guarda_nada(): void
    {
        self::assertNull(indice_orquestador::guardarAD1(self::LEGAJO, 2025));

        $evaluaciones = toba::db('desempenio')->consultar_sentencia(
            'SELECT count(*) c FROM indice.evaluacion WHERE docente_id = :d',
            ['d' => self::LEGAJO],
        );
        self::assertSame(0, (int) $evaluaciones[0]['c']);
    }

    private function crearFicha(int $anio, int $categoria_id, int $dedicacion_id): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_guardar_ad1', :anio, :categoria_id, :dedicacion_id)
             RETURNING id",
            [
                'dni' => self::DNI,
                'legajo' => self::LEGAJO,
                'anio' => $anio,
                'categoria_id' => $categoria_id,
                'dedicacion_id' => $dedicacion_id,
            ],
        );
        return (int) $fila[0]['id'];
    }

    private function crearFormacionAcademica(int $ficha_id, int $tipo_formacion, string $titulo): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.formacion_academica (ficha_id, tipo_formacion, titulo)
             VALUES (:ficha_id, :tipo_formacion, :titulo)',
            ['ficha_id' => $ficha_id, 'tipo_formacion' => $tipo_formacion, 'titulo' => $titulo],
        );
    }

    private function limpiar(): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            "DELETE FROM indice.evaluacion_item WHERE evaluacion_id IN
                (SELECT id FROM indice.evaluacion WHERE docente_id = :d)",
            ['d' => self::LEGAJO],
        );
        toba::db('desempenio')->ejecutar_sentencia(
            "DELETE FROM indice.evaluacion_dimension WHERE evaluacion_id IN
                (SELECT id FROM indice.evaluacion WHERE docente_id = :d)",
            ['d' => self::LEGAJO],
        );
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM indice.evaluacion WHERE docente_id = :d',
            ['d' => self::LEGAJO],
        );
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.formacion_academica
              WHERE ficha_id IN (SELECT id FROM public.ficha WHERE legajo_doc = :legajo)',
            ['legajo' => self::LEGAJO],
        );
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.ficha WHERE legajo_doc = :legajo',
            ['legajo' => self::LEGAJO],
        );
    }
}
