<?php

declare(strict_types=1);

/**
 * Test de integración: sin mocks, contra una ficha real (de prueba) en la
 * base `desempenio`. Valida indice_repositorio::categoria_dedicacion() —
 * docs/indice/Mapeo-origen.md, sección Transversales.
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceRepositorioTransversalesTest extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000002;
    private const ANIO = 1900;
    private const DNI = 90000002;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_categoria_y_dedicacion_de_una_ficha(): void
    {
        // 4 = JTP, 3 = Simple -> 'DS' (public.categorias_doc / public.dedicaciones).
        $this->crearFicha(categoria_id: 4, dedicacion_id: 3);

        self::assertSame(
            ['categoria' => 'JTP', 'dedicacion' => 'DS'],
            indice_repositorio::categoria_dedicacion(self::LEGAJO, self::ANIO),
        );
    }

    public function test_dedicacion_full_se_trata_como_exclusiva(): void
    {
        // 1 = Titular, 4 = Full -> tratada como 'EX' (decisión confirmada, no normativa escrita).
        $this->crearFicha(categoria_id: 1, dedicacion_id: 4);

        self::assertSame(
            ['categoria' => 'T', 'dedicacion' => 'EX'],
            indice_repositorio::categoria_dedicacion(self::LEGAJO, self::ANIO),
        );
    }

    public function test_legajo_o_anio_sin_ficha_da_null(): void
    {
        self::assertNull(indice_repositorio::categoria_dedicacion(self::LEGAJO, self::ANIO));
    }

    public function test_categoria_id_sin_mapeo_lanza_excepcion(): void
    {
        $this->crearFicha(categoria_id: 99, dedicacion_id: 1);

        $this->expectException(RuntimeException::class);
        indice_repositorio::categoria_dedicacion(self::LEGAJO, self::ANIO);
    }

    private function crearFicha(int $categoria_id, int $dedicacion_id): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_transversales', :anio, :categoria_id, :dedicacion_id)
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

    private function limpiar(): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.ficha WHERE legajo_doc = :legajo',
            ['legajo' => self::LEGAJO],
        );
    }
}
