<?php

declare(strict_types=1);

/**
 * Test de integración: contra la config real de indice.* sembrada por
 * sql/indice/02_siembra_docencia.sql (no crea/borra nada, es catálogo).
 * Valida indice_repositorio::componentes()/umbral() para AD1 — ver
 * docs/indice/Mapeo-origen.md, sección AD1.
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceRepositorioConfiguracionTest extends \PHPUnit\Framework\TestCase
{
    public function test_componentes_de_ad1_reproduce_el_cuadro_2_4_1_1(): void
    {
        $componentes = indice_repositorio::componentes('AD1', 2025);

        self::assertCount(1, $componentes);

        $componente = $componentes[0];
        // tipo='lookup', no 'producto': el proveedor no emite 'cantidad'
        // (docs/indice/Mapeo-origen.md, AD1, decisión 1) — ver
        // sql/indice/05_ad1_fix_tipo_componente.sql.
        self::assertSame('lookup', $componente->tipo);
        self::assertSame(1, $componente->termino);
        self::assertNull($componente->campo);
        self::assertSame('tipo_formacion', $componente->campoClave);
        self::assertNull($componente->campoClave2);
        // Claves = public.formacion.id en texto (docs/indice/Mapeo-origen.md,
        // AD1, decisión 5): 1 Pregrado, 2 Grado, 3 Maestría, 4 Doctorado,
        // 5 Posdoctorado, 6 Especialización, 7 Diplomado — el mismo id que
        // emite indice_repositorio::items_formacion_academica() en 'tipo_formacion'.
        self::assertSame(
            [
                '1' => '1.000000',
                '2' => '3.000000',
                '3' => '3.000000',
                '4' => '5.000000',
                '5' => '6.000000',
                '6' => '1.000000',
                '7' => '0.500000',
            ],
            $componente->tablaValoracion,
        );
    }

    public function test_umbral_de_ad1_varia_por_categoria(): void
    {
        self::assertSame(
            ['minimo' => '6.000000', 'superior' => '8.000000'],
            indice_repositorio::umbral('AD1', 'T', 'EX', 2025),
        );
        self::assertSame(
            ['minimo' => '3.000000', 'superior' => '5.000000'],
            indice_repositorio::umbral('AD1', 'JTP', 'DS', 2025),
        );
        self::assertSame(
            ['minimo' => '0.000000', 'superior' => '2.000000'],
            indice_repositorio::umbral('AD1', 'AY2', 'DS', 2025),
        );
    }

    public function test_umbral_de_combinacion_categoria_dedicacion_inexistente_lanza_excepcion(): void
    {
        // AY2 sólo existe con dedicación DS (sql/indice/02_siembra_docencia.sql, tabla cargo).
        $this->expectException(RuntimeException::class);
        indice_repositorio::umbral('AD1', 'AY2', 'EX', 2025);
    }

    public function test_anio_sin_normativa_cargada_lanza_excepcion(): void
    {
        $this->expectException(RuntimeException::class);
        indice_repositorio::componentes('AD1', 1900);
    }

    /**
     * Punta a punta con datos reales: ficha -> ítems (indice_repositorio) ->
     * config real (indice_repositorio) -> motor puro (EvaluadorComponentes +
     * Categorizador). Es la prueba que faltaba para confirmar que AD1 se
     * puede calcular de verdad, no sólo en fixtures en memoria
     * (test/indice/Apoyo/ConfiguracionAD.php).
     *
     * Docente JTP-DS: umbral 3-5 (Cuadro 2.4.1.2). Grado (id 2, w=3) +
     * Doctorado (id 4, w=5) -> PD1 = 8 -> supera el superior -> AD1 = 3.
     */
    public function test_calculo_real_de_ad1_de_punta_a_punta(): void
    {
        $legajo = 900000099;
        $anio = 2025;
        $this->limpiarFicha($legajo);

        $ficha_id = $this->crearFicha($legajo, 90000099, $anio);
        $this->crearFormacionAcademica($ficha_id, 2, 'Ingeniero Agrónomo'); // Grado, w=3
        $this->crearFormacionAcademica($ficha_id, 4, 'Doctor en Agronomía'); // Doctorado, w=5

        $items = indice_repositorio::items_formacion_academica($legajo, $anio);
        $componentes = indice_repositorio::componentes('AD1', $anio);
        $umbral = indice_repositorio::umbral('AD1', 'JTP', 'DS', $anio);

        $evaluador = new \Pruebas\Indice\Motor\EvaluadorComponentes();
        $puntaje = '0';
        foreach ($items as $item) {
            $puntaje = bcadd($puntaje, $evaluador->evaluarItem($componentes, $item), 6);
        }

        self::assertSame('8.000000', $puntaje);
        self::assertSame(
            3,
            (new \Pruebas\Indice\Motor\Categorizador())->categorizar($puntaje, $umbral['minimo'], $umbral['superior']),
        );

        $this->limpiarFicha($legajo);
    }

    private function crearFicha(int $legajo, int $dni, int $anio): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio)
             VALUES (:dni, :legajo, 'test_indice_config', :anio)
             RETURNING id",
            ['dni' => $dni, 'legajo' => $legajo, 'anio' => $anio],
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

    private function limpiarFicha(int $legajo): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.formacion_academica
              WHERE ficha_id IN (SELECT id FROM public.ficha WHERE legajo_doc = :legajo)',
            ['legajo' => $legajo],
        );
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.ficha WHERE legajo_doc = :legajo',
            ['legajo' => $legajo],
        );
    }
}
