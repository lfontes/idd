<?php

declare(strict_types=1);

/**
 * Test de integración: indice_orquestador::cerrarPeriodo() — arma
 * indice.evaluacion_periodo (fc/fck, enlace ficha<->evaluación) para todos
 * los años con ficha de un docente. Hasta esta sesión (2026-08-28) esto se
 * completaba a mano; el mapeo de licencias que lo bloqueaba
 * (docs/indice/Mapeo-origen.md) ya está resuelto en
 * indice_repositorio::licencias().
 *
 * El caso de año 1 (5 meses de licencia con goce, 3 sin goce -> fc=1/3,
 * fck=3.000000 exacto) reproduce a propósito
 * CorreccionTemporalTest::test_fck_usa_precision_completa_no_el_fc_redondeado,
 * ahora pasando por la conversión días/30 real (150 días = 5 meses, 90 días
 * = 3 meses) en vez de meses ya calculados a mano.
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceOrquestadorCerrarPeriodoTest extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000013;
    private const DNI = 90000013;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_sin_fichas_no_hace_nada(): void
    {
        indice_orquestador::cerrarPeriodo(self::LEGAJO);

        $evaluaciones = toba::db('desempenio')->consultar_sentencia(
            'SELECT count(*) c FROM indice.evaluacion WHERE docente_id = :d',
            ['d' => self::LEGAJO],
        );
        self::assertSame(0, (int) $evaluaciones[0]['c']);
    }

    public function test_arma_evaluacion_periodo_con_fc_fck_de_la_serie_completa(): void
    {
        $ficha1 = $this->crearFicha(2024, categoria_id: 3, dedicacion_id: 2); // AJ, SE
        $this->crearLicencia($ficha1, dias: 150, goce: true); // 150/30 = 5 meses
        $this->crearLicencia($ficha1, dias: 90, goce: false); // 90/30 = 3 meses
        $ficha2 = $this->crearFicha(2025, categoria_id: 3, dedicacion_id: 2); // AJ, SE, sin licencias

        indice_orquestador::cerrarPeriodo(self::LEGAJO);

        $periodos = toba::db('desempenio')->consultar_sentencia(
            "SELECT ep.anio, ep.k, ep.informe_id, ep.categoria, ep.dedicacion,
                    ep.lic_con_goce, ep.lic_sin_goce, ep.fc, ep.fck
               FROM indice.evaluacion_periodo ep
               JOIN indice.evaluacion e ON e.id = ep.evaluacion_id
              WHERE e.docente_id = :d
              ORDER BY ep.anio",
            ['d' => self::LEGAJO],
        );

        self::assertCount(2, $periodos);

        self::assertSame(2024, (int) $periodos[0]['anio']);
        self::assertSame(1, (int) $periodos[0]['k']);
        self::assertSame($ficha1, (int) $periodos[0]['informe_id']);
        self::assertSame('AJ', $periodos[0]['categoria']);
        self::assertSame('SE', $periodos[0]['dedicacion']);
        self::assertSame('5.00', $periodos[0]['lic_con_goce']);
        self::assertSame('3.00', $periodos[0]['lic_sin_goce']);
        self::assertSame('0.333333', $periodos[0]['fc']);
        self::assertSame('3.000000', $periodos[0]['fck']); // exacto: 1/(1/3)

        self::assertSame(2025, (int) $periodos[1]['anio']);
        self::assertSame(2, (int) $periodos[1]['k']);
        self::assertSame($ficha2, (int) $periodos[1]['informe_id']);
        self::assertSame('0.00', $periodos[1]['lic_con_goce']);
        self::assertSame('1.000000', $periodos[1]['fc']);
        self::assertSame('1.500000', $periodos[1]['fck']); // 2/(1/3+1)
    }

    public function test_recerrar_no_duplica_filas(): void
    {
        $this->crearFicha(2024, categoria_id: 3, dedicacion_id: 2);

        indice_orquestador::cerrarPeriodo(self::LEGAJO);
        indice_orquestador::cerrarPeriodo(self::LEGAJO);

        $periodos = toba::db('desempenio')->consultar_sentencia(
            "SELECT count(*) c FROM indice.evaluacion_periodo ep
               JOIN indice.evaluacion e ON e.id = ep.evaluacion_id
              WHERE e.docente_id = :d",
            ['d' => self::LEGAJO],
        );
        self::assertSame(1, (int) $periodos[0]['c']);
    }

    /**
     * El objetivo real: después de cerrarPeriodo() + guardarDimension(),
     * indice.vista_idd (la que alimenta el Cuadro de Indicadores de
     * ci_ficha) trae la fila sin ningún insert manual a evaluacion_periodo.
     */
    public function test_vista_idd_queda_poblada_sin_intervencion_manual(): void
    {
        $ficha_id = $this->crearFicha(2024, categoria_id: 4, dedicacion_id: 3); // JTP, DS
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.formacion_academica (ficha_id, tipo_formacion, titulo)
             VALUES (:ficha_id, 2, :titulo)',
            ['ficha_id' => $ficha_id, 'titulo' => 'Ingeniero Agrónomo'],
        );

        indice_orquestador::cerrarPeriodo(self::LEGAJO);
        indice_orquestador::guardarDimension('AD1', self::LEGAJO, 2024);

        $vista = toba::db('desempenio')->consultar_sentencia(
            'SELECT ad1 FROM indice.vista_idd WHERE ficha_id = :ficha_id',
            ['ficha_id' => $ficha_id],
        );

        self::assertCount(1, $vista);
        self::assertNotNull($vista[0]['ad1']);
    }

    private function crearFicha(int $anio, int $categoria_id, int $dedicacion_id): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_cerrar_periodo', :anio, :categoria_id, :dedicacion_id)
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

    private function crearLicencia(int $ficha_id, int $dias, bool $goce): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.licencias (ficha_id, dias, goce) VALUES (:ficha_id, :dias, :goce)',
            ['ficha_id' => $ficha_id, 'dias' => $dias, 'goce' => $goce ? 't' : 'f'],
        );
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
            'DELETE FROM public.licencias
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
