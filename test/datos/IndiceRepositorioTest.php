<?php

declare(strict_types=1);

/**
 * Test de integración: sin mocks, contra una ficha real (de prueba) en la
 * base `desempenio`. Lo que se valida es el SQL de indice_repositorio.php
 * (join ficha/formacion_academica, columnas reales) — ver
 * docs/indice/mapeo-origen.md, sección AD1.
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba), no bajo phpunit.xml
 * (test/indice/, motor puro). Requiere el contenedor `test`:
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceRepositorioTest extends \PHPUnit\Framework\TestCase
{
    // Sentinela bien alejado de legajos reales, para no pisar datos existentes.
    private const LEGAJO = 900000001;
    private const ANIO = 1900;
    private const DNI = 90000001;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_items_formacion_academica_de_una_ficha_con_formaciones(): void
    {
        $ficha_id = $this->crear_ficha();
        $id_grado = $this->crear_formacion_academica($ficha_id, tipo_formacion: 2, titulo: 'Ingeniero Agrónomo', institucion: 'UNCuyo', normativa: 'ORD 10/20', anio: 2010);
        $id_doctorado = $this->crear_formacion_academica($ficha_id, tipo_formacion: 4, titulo: 'Doctor en Agronomía', institucion: 'UNCuyo', normativa: null, anio: 2018);

        $items = indice_repositorio::items_formacion_academica(self::LEGAJO, self::ANIO);

        self::assertCount(2, $items);

        self::assertSame('2', $items[0]->valor('tipo_formacion'));
        self::assertSame('Ingeniero Agrónomo', $items[0]->descripcion);
        self::assertSame('formacion_academica', $items[0]->origenTabla);
        self::assertSame($id_grado, $items[0]->origenId);
        self::assertSame(
            ['institucion' => 'UNCuyo', 'anio' => 2010, 'normativa' => 'ORD 10/20'],
            $items[0]->detalle(),
        );

        self::assertSame('4', $items[1]->valor('tipo_formacion'));
        self::assertSame('Doctor en Agronomía', $items[1]->descripcion);
        self::assertSame($id_doctorado, $items[1]->origenId);
        self::assertSame(
            ['institucion' => 'UNCuyo', 'anio' => 2018, 'normativa' => null],
            $items[1]->detalle(),
        );

        // No emite 'cantidad': dos formaciones son dos ítems, no un ítem con cantidad=2
        // (docs/indice/mapeo-origen.md, decisión 1).
        self::assertFalse($items[0]->tiene('cantidad'));
    }

    public function test_dos_formaciones_del_mismo_tipo_no_se_deduplican(): void
    {
        $ficha_id = $this->crear_ficha();
        $this->crear_formacion_academica($ficha_id, tipo_formacion: 4, titulo: 'Doctor en Agronomía', institucion: null, normativa: null, anio: 2015);
        $this->crear_formacion_academica($ficha_id, tipo_formacion: 4, titulo: 'Doctor en Estadística', institucion: null, normativa: null, anio: 2020);

        $items = indice_repositorio::items_formacion_academica(self::LEGAJO, self::ANIO);

        self::assertCount(2, $items);
        self::assertSame('4', $items[0]->valor('tipo_formacion'));
        self::assertSame('4', $items[1]->valor('tipo_formacion'));
    }

    public function test_ficha_sin_formaciones_declaradas_da_lista_vacia(): void
    {
        $this->crear_ficha();
        self::assertSame([], indice_repositorio::items_formacion_academica(self::LEGAJO, self::ANIO));
    }

    public function test_legajo_o_anio_sin_ficha_da_lista_vacia(): void
    {
        self::assertSame([], indice_repositorio::items_formacion_academica(self::LEGAJO, self::ANIO));
    }

    private function crear_ficha(): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio)
             VALUES (:dni, :legajo, 'test_indice_ad1', :anio)
             RETURNING id",
            ['dni' => self::DNI, 'legajo' => self::LEGAJO, 'anio' => self::ANIO],
        );
        return (int) $fila[0]['id'];
    }

    private function crear_formacion_academica(
        int $ficha_id,
        int $tipo_formacion,
        string $titulo,
        ?string $institucion,
        ?string $normativa,
        ?int $anio,
    ): int {
        $fila = toba::db('desempenio')->consultar_sentencia(
            'INSERT INTO public.formacion_academica (ficha_id, tipo_formacion, titulo, institucion, normativa, anio)
             VALUES (:ficha_id, :tipo_formacion, :titulo, :institucion, :normativa, :anio)
             RETURNING id',
            [
                'ficha_id' => $ficha_id,
                'tipo_formacion' => $tipo_formacion,
                'titulo' => $titulo,
                'institucion' => $institucion,
                'normativa' => $normativa,
                'anio' => $anio,
            ],
        );
        return (int) $fila[0]['id'];
    }

    private function limpiar(): void
    {
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
