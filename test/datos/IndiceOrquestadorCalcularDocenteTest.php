<?php

declare(strict_types=1);

/**
 * Test de integración: indice_orquestador::calcularDocente()/calcularTodosLosDocentes()
 * — el batch que faltaba para que indice.vista_idd se pueble para cualquier
 * legajo, no sólo el de prueba de la demo (sesión 2026-08-28). Compone
 * cerrarPeriodo() + guardarDimension() de todas las dimensiones sembradas
 * para cada año con ficha.
 *
 * calcularTodosLosDocentes() no se prueba acá corriéndolo: recorre
 * indice_repositorio::legajos(), que trae TODOS los legajos reales de la
 * base (227 al momento de escribir esto) -- correrlo en un test escribiría
 * indice.evaluacion* para docentes reales como efecto colateral del test.
 * Se prueba calcularDocente() (la unidad reutilizable) con un legajo
 * sintético, y se confía en que el loop de calcularTodosLosDocentes() es
 * trivial (sin lógica propia).
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceOrquestadorCalcularDocenteTest extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000014;
    private const DNI = 90000014;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_legajos_incluye_al_legajo_recien_creado(): void
    {
        self::assertNotContains(self::LEGAJO, indice_repositorio::legajos());

        $this->crearFicha(2025, categoria_id: 4, dedicacion_id: 3);

        self::assertContains(self::LEGAJO, indice_repositorio::legajos());
    }

    /**
     * Bug real encontrado corriendo calcularTodosLosDocentes() contra la
     * base (2026-08-28): una ficha con anio NULL (existe en datos reales,
     * además de fichas válidas del mismo docente) hacía fallar el cierre de
     * período completo -- (int) NULL = 0 se colaba como "año 0".
     */
    public function test_ficha_con_anio_null_no_rompe_el_cierre_de_periodo(): void
    {
        $this->crearFicha(2025, categoria_id: 4, dedicacion_id: 3);
        toba::db('desempenio')->ejecutar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_calcular_docente', NULL, 4, 3)",
            ['dni' => self::DNI, 'legajo' => self::LEGAJO],
        );

        $fichas = indice_repositorio::fichas(self::LEGAJO);
        self::assertCount(1, $fichas); // la de anio NULL queda afuera

        indice_orquestador::calcularDocente(self::LEGAJO);

        $periodos = toba::db('desempenio')->consultar_sentencia(
            "SELECT anio FROM indice.evaluacion_periodo ep
               JOIN indice.evaluacion e ON e.id = ep.evaluacion_id
              WHERE e.docente_id = :d",
            ['d' => self::LEGAJO],
        );
        self::assertSame([2025], array_map(static fn (array $p) => (int) $p['anio'], $periodos));
    }

    public function test_calcula_todas_las_dimensiones_sembradas_de_todos_los_anios_con_ficha(): void
    {
        $ficha1 = $this->crearFicha(2024, categoria_id: 4, dedicacion_id: 3); // JTP, DS
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.formacion_academica (ficha_id, tipo_formacion, titulo)
             VALUES (:ficha_id, 2, :titulo)',
            ['ficha_id' => $ficha1, 'titulo' => 'Ingeniero Agrónomo'],
        );
        $ficha2 = $this->crearFicha(2025, categoria_id: 4, dedicacion_id: 3);
        // ficha2 sin ítems declarados en ninguna dimensión: igual debe quedar
        // evaluacion_dimension en 0 para cada una, no en blanco.

        indice_orquestador::calcularDocente(self::LEGAJO);

        $periodos = toba::db('desempenio')->consultar_sentencia(
            "SELECT count(*) c FROM indice.evaluacion_periodo ep
               JOIN indice.evaluacion e ON e.id = ep.evaluacion_id
              WHERE e.docente_id = :d",
            ['d' => self::LEGAJO],
        );
        self::assertSame(2, (int) $periodos[0]['c']); // un renglón por año

        $dimensiones = toba::db('desempenio')->consultar_sentencia(
            "SELECT ed.anio, dim.codigo, ed.puntaje
               FROM indice.evaluacion_dimension ed
               JOIN indice.evaluacion e ON e.id = ed.evaluacion_id
               JOIN indice.dimension dim ON dim.id = ed.dimension_id
              WHERE e.docente_id = :d
              ORDER BY ed.anio, dim.orden",
            ['d' => self::LEGAJO],
        );
        // 8 dimensiones (AD1..AD8) x 2 años = 16 renglones, ninguno se salteó.
        self::assertCount(16, $dimensiones);

        $ad1_2024 = array_values(array_filter(
            $dimensiones,
            static fn (array $d) => (int) $d['anio'] === 2024 && $d['codigo'] === 'AD1',
        ))[0];
        self::assertSame('3.000000', $ad1_2024['puntaje']); // Grado, tipo_formacion=2, w=3 (ver AD1)

        $ad1_2025 = array_values(array_filter(
            $dimensiones,
            static fn (array $d) => (int) $d['anio'] === 2025 && $d['codigo'] === 'AD1',
        ))[0];
        self::assertSame('0.000000', $ad1_2025['puntaje']); // sin formación declarada ese año

        $vista = toba::db('desempenio')->consultar_sentencia(
            'SELECT ficha_id, ad1 FROM indice.vista_idd WHERE ficha_id IN (:f1, :f2) ORDER BY ficha_id',
            ['f1' => $ficha1, 'f2' => $ficha2],
        );
        self::assertCount(2, $vista);
    }

    private function crearFicha(int $anio, int $categoria_id, int $dedicacion_id): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_calcular_docente', :anio, :categoria_id, :dedicacion_id)
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

    private function limpiar(): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            "DELETE FROM indice.evaluacion_periodo WHERE evaluacion_id IN
                (SELECT id FROM indice.evaluacion WHERE docente_id = :d)",
            ['d' => self::LEGAJO],
        );
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
