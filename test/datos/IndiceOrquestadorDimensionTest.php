<?php

declare(strict_types=1);

/**
 * Test de integración: indice_orquestador::calcularDimension()/guardarDimension()
 * genérico, despachando por indice.dimension.proveedor — a diferencia de
 * IndiceOrquestadorTest/IndiceOrquestadorGuardarAD1Test (calcularAD1/guardarAD1,
 * que ahora son wrappers de compatibilidad sobre estos métodos genéricos).
 * Prueba con AD2, el primer proveedor agregado además de AD1.
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceOrquestadorDimensionTest extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000006;
    private const DNI = 90000006;
    private const ANIO = 2025;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_calcula_ad2_de_un_docente_jtp_ds(): void
    {
        $ficha_id = $this->crearFicha(categoria_id: 4, dedicacion_id: 3); // JTP, Simple -> DS
        $this->crearActualizacion($ficha_id, tipo_act: 1, certificado: 1, horas: 25); // Curso aprobado

        $resultado = indice_orquestador::calcularDimension('AD2', self::LEGAJO, self::ANIO);

        self::assertNotNull($resultado);
        self::assertSame('AD2', $resultado->dimension);
        self::assertSame('1.000000', $resultado->puntaje);
        self::assertSame('1.000000', $resultado->minimo);
        self::assertSame('5.000000', $resultado->superior);
        self::assertSame(1, $resultado->valoracion); // PD2=1 alcanza el mínimo (1) de JTP/DS -> AD2=1
    }

    public function test_docente_sin_ficha_ese_anio_da_null(): void
    {
        self::assertNull(indice_orquestador::calcularDimension('AD2', self::LEGAJO, self::ANIO));
    }

    public function test_guarda_evaluacion_dimension_e_item_de_ad2(): void
    {
        $ficha_id = $this->crearFicha(categoria_id: 4, dedicacion_id: 3);
        $this->crearActualizacion($ficha_id, tipo_act: 1, certificado: 1, horas: 25);

        $resultado = indice_orquestador::guardarDimension('AD2', self::LEGAJO, self::ANIO);

        self::assertNotNull($resultado);
        self::assertSame('1.000000', $resultado->puntaje);

        $dimension = toba::db('desempenio')->consultar_sentencia(
            "SELECT ed.puntaje, ed.valoracion
               FROM indice.evaluacion_dimension ed
               JOIN indice.evaluacion e ON e.id = ed.evaluacion_id
               JOIN indice.dimension dim ON dim.id = ed.dimension_id
              WHERE e.docente_id = :d AND ed.anio = :anio AND dim.codigo = 'AD2'",
            ['d' => self::LEGAJO, 'anio' => self::ANIO],
        );
        self::assertCount(1, $dimension);
        self::assertSame('1.000000', $dimension[0]['puntaje']);

        $items = toba::db('desempenio')->consultar_sentencia(
            "SELECT origen_tabla, puntaje FROM indice.evaluacion_item ei
               JOIN indice.evaluacion e ON e.id = ei.evaluacion_id
               JOIN indice.dimension dim ON dim.id = ei.dimension_id
              WHERE e.docente_id = :d AND ei.anio = :anio AND dim.codigo = 'AD2'",
            ['d' => self::LEGAJO, 'anio' => self::ANIO],
        );
        self::assertCount(1, $items);
        self::assertSame('c32_actualizacion', $items[0]['origen_tabla']);
        self::assertSame('1.000000', $items[0]['puntaje']);
    }

    public function test_calcularad1_deprecado_delega_en_calculardimension(): void
    {
        $ficha_id = $this->crearFicha(categoria_id: 4, dedicacion_id: 3);
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.formacion_academica (ficha_id, tipo_formacion, titulo)
             VALUES (:ficha_id, 2, :titulo)',
            ['ficha_id' => $ficha_id, 'titulo' => 'Ingeniero Agrónomo'],
        );

        $viaDeprecado = indice_orquestador::calcularAD1(self::LEGAJO, self::ANIO);
        $viaGenerico = indice_orquestador::calcularDimension('AD1', self::LEGAJO, self::ANIO);

        self::assertSame($viaDeprecado->puntaje, $viaGenerico->puntaje);
        self::assertSame($viaDeprecado->valoracion, $viaGenerico->valoracion);
    }

    private function crearFicha(int $categoria_id, int $dedicacion_id): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_orq_dim', :anio, :categoria_id, :dedicacion_id)
             RETURNING id",
            [
                'dni' => self::DNI,
                'legajo' => self::LEGAJO,
                'anio' => self::ANIO,
                'categoria_id' => $categoria_id,
                'dedicacion_id' => $dedicacion_id,
            ],
        );
        return (int) $fila[0]['id'];
    }

    private function crearActualizacion(int $ficha_id, int $tipo_act, int $certificado, int $horas): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.c32_actualizacion (ficha_id, tipo_act, nombre, certificado, horas)
             VALUES (:ficha_id, :tipo_act, :nombre, :certificado, :horas)',
            [
                'ficha_id' => $ficha_id,
                'tipo_act' => $tipo_act,
                'nombre' => 'Actividad de prueba',
                'certificado' => $certificado,
                'horas' => $horas,
            ],
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
            'DELETE FROM public.c32_actualizacion
              WHERE ficha_id IN (SELECT id FROM public.ficha WHERE legajo_doc = :legajo)',
            ['legajo' => self::LEGAJO],
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
