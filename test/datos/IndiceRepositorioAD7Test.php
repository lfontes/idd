<?php

declare(strict_types=1);

/**
 * Test de integración: AD7 — Formación de recursos humanos
 * (docs/indice/mapeo-origen.md, sección AD7). Sin mocks, contra una ficha
 * real (de prueba) en la base `desempenio`.
 *
 * Mismo bug que AD4/AD5/AD6: la tabla de valoración estaba keyed por texto,
 * y además le faltaba 'Tesista de grado'. Corregido en
 * sql/indice/11_ad678_fix_valoracion_por_id.sql (confirmado por el usuario).
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceRepositorioAD7Test extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000011;
    private const DNI = 90000011;
    private const ANIO = 2025;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_items_formacion_rrhh_traduce_el_campo(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearFormacion($ficha_id, categoria_id: 1, descripcion: 'Docente formado');
        $this->crearFormacion($ficha_id, categoria_id: 5, descripcion: 'Tesista de grado formado'); // el que faltaba

        $items = indice_repositorio::items_formacion_rrhh(self::LEGAJO, self::ANIO);

        self::assertCount(2, $items);
        self::assertSame('1', $items[0]->valor('categoria_rrhh'));
        self::assertSame('5', $items[1]->valor('categoria_rrhh'));
        self::assertSame('c37_formacion_docec', $items[0]->origenTabla);
    }

    public function test_componentes_de_ad7_reproduce_las_reglas_sembradas(): void
    {
        $componentes = indice_repositorio::componentes('AD7', self::ANIO);

        self::assertCount(1, $componentes);
        self::assertSame('lookup', $componentes[0]->tipo);
        self::assertSame('categoria_rrhh', $componentes[0]->campoClave);
        self::assertSame(
            [
                '1' => '10.000000', // Docente
                '2' => '5.000000',  // Adscripto
                '3' => '3.000000',  // Pasante
                '4' => '3.000000',  // Concurrente
                '5' => '1.000000',  // Tesista de grado
            ],
            $componentes[0]->tablaValoracion,
        );
    }

    public function test_umbral_de_ad7_jtp_ds(): void
    {
        self::assertSame(
            ['minimo' => '1.000000', 'superior' => '15.000000'],
            indice_repositorio::umbral('AD7', 'JTP', 'DS', self::ANIO),
        );
    }

    /**
     * Punta a punta: Docente (10) + Tesista de grado (1) = 11, dentro de
     * [1, 15] de JTP/DS -> AD7 = 1.
     */
    public function test_calculo_real_de_ad7_de_punta_a_punta(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearFormacion($ficha_id, categoria_id: 1, descripcion: 'Item 1');
        $this->crearFormacion($ficha_id, categoria_id: 5, descripcion: 'Item 2');

        $items = indice_repositorio::items_formacion_rrhh(self::LEGAJO, self::ANIO);
        $componentes = indice_repositorio::componentes('AD7', self::ANIO);
        $umbral = indice_repositorio::umbral('AD7', 'JTP', 'DS', self::ANIO);

        $evaluador = new \Pruebas\Indice\Motor\EvaluadorComponentes();
        $puntaje = '0';
        foreach ($items as $item) {
            $puntaje = bcadd($puntaje, $evaluador->evaluarItem($componentes, $item), 6);
        }

        self::assertSame('11.000000', $puntaje);
        self::assertSame(
            1,
            (new \Pruebas\Indice\Motor\Categorizador())->categorizar($puntaje, $umbral['minimo'], $umbral['superior']),
        );
    }

    private function crearFicha(): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_ad7', :anio, 4, 3)
             RETURNING id",
            ['dni' => self::DNI, 'legajo' => self::LEGAJO, 'anio' => self::ANIO],
        );
        return (int) $fila[0]['id'];
    }

    private function crearFormacion(int $ficha_id, int $categoria_id, string $descripcion): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.c37_formacion_docec (ficha_id, categoria_id, descripcion)
             VALUES (:ficha_id, :categoria_id, :descripcion)',
            ['ficha_id' => $ficha_id, 'categoria_id' => $categoria_id, 'descripcion' => $descripcion],
        );
    }

    private function limpiar(): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.c37_formacion_docec
              WHERE ficha_id IN (SELECT id FROM public.ficha WHERE legajo_doc = :legajo)',
            ['legajo' => self::LEGAJO],
        );
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.ficha WHERE legajo_doc = :legajo',
            ['legajo' => self::LEGAJO],
        );
    }
}
