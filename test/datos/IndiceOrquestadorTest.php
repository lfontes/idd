<?php

declare(strict_types=1);

/**
 * Test de integración: contra una ficha real (de prueba) y la config real
 * de indice.* — sin glue code a mano, a diferencia de
 * IndiceRepositorioConfiguracionTest::test_calculo_real_de_ad1_de_punta_a_punta,
 * que arma el mismo cálculo pieza por pieza para probar cada pieza.
 * Acá se prueba indice_orquestador::calcularAD1() como lo usaría un CI.
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceOrquestadorTest extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000003;
    private const ANIO = 2025;
    private const DNI = 90000003;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_calcula_ad1_de_un_docente_jtp_ds_con_formaciones(): void
    {
        $ficha_id = $this->crearFicha(categoria_id: 4, dedicacion_id: 3); // JTP, Simple -> DS
        $this->crearFormacionAcademica($ficha_id, tipo_formacion: 2, titulo: 'Ingeniero Agrónomo'); // Grado, w=3
        $this->crearFormacionAcademica($ficha_id, tipo_formacion: 4, titulo: 'Doctor en Agronomía'); // Doctorado, w=5

        $resultado = indice_orquestador::calcularAD1(self::LEGAJO, self::ANIO);

        self::assertNotNull($resultado);
        self::assertSame('AD1', $resultado->dimension);
        self::assertSame(self::ANIO, $resultado->anio);
        self::assertSame('8.000000', $resultado->puntaje);
        self::assertSame('3.000000', $resultado->minimo);
        self::assertSame('5.000000', $resultado->superior);
        self::assertSame(3, $resultado->valoracion); // PD1=8 supera el superior (5) -> AD1=3
    }

    public function test_calcula_ad1_de_un_docente_sin_formaciones_declaradas(): void
    {
        $this->crearFicha(categoria_id: 6, dedicacion_id: 3); // Ayudante 2da, Simple -> AY2/DS (umbral 0-2)

        $resultado = indice_orquestador::calcularAD1(self::LEGAJO, self::ANIO);

        self::assertNotNull($resultado);
        self::assertSame('0.000000', $resultado->puntaje);
        self::assertSame(1, $resultado->valoracion); // PD1=0 alcanza el mínimo (0) de AY2/DS -> AD1=1
    }

    public function test_docente_sin_ficha_ese_anio_da_null(): void
    {
        self::assertNull(indice_orquestador::calcularAD1(self::LEGAJO, self::ANIO));
    }

    private function crearFicha(int $categoria_id, int $dedicacion_id): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_orquestador', :anio, :categoria_id, :dedicacion_id)
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
