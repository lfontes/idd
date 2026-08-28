<?php

declare(strict_types=1);

/**
 * Test de integración: AD3 — Docencia en carreras de pregrado y grado
 * (docs/indice/mapeo-origen.md, sección AD3). Sin mocks, contra una ficha
 * real (de prueba) en la base `desempenio`.
 *
 * A diferencia de AD1/AD2, el mapeo no requirió confirmación del usuario:
 * public.c33_docec_facultad ya trae carga_horario/cant_inscriptos como
 * snapshot propio (no consulta Guaraní en el momento del cálculo), y
 * tipo_esp_curricular_id calza id a id con indice.valoracion (tabla_id 2).
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceRepositorioAD3Test extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000007;
    private const DNI = 90000007;
    private const ANIO = 2025;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_items_espacios_curriculares_traduce_los_campos(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearEspacio($ficha_id, tipo_esp_curricular_id: 1, carga_horario: 90, cant_inscriptos: 90); // Teórica
        $this->crearEspacio($ficha_id, tipo_esp_curricular_id: 2, carga_horario: null, cant_inscriptos: 30); // sin carga_horario (dato real: hay filas así)

        $items = indice_repositorio::items_espacios_curriculares(self::LEGAJO, self::ANIO);

        self::assertCount(2, $items);

        self::assertSame('90', $items[0]->valor('carga_horaria'));
        self::assertSame('90', $items[0]->valor('estudiantes_inscriptos'));
        self::assertSame('1', $items[0]->valor('tipo_espacio'));
        self::assertSame('c33_docec_facultad', $items[0]->origenTabla);

        self::assertNull($items[1]->valor('carga_horaria'));
        self::assertSame('30', $items[1]->valor('estudiantes_inscriptos'));
        self::assertSame('2', $items[1]->valor('tipo_espacio'));
    }

    public function test_componentes_de_ad3_reproduce_las_reglas_sembradas(): void
    {
        $componentes = indice_repositorio::componentes('AD3', self::ANIO);

        self::assertCount(3, $componentes);
        foreach ($componentes as $componente) {
            self::assertSame(1, $componente->termino); // multiplicativos: carga/Che x inscriptos/Cee x tipo
        }

        self::assertSame('proporcion', $componentes[0]->tipo);
        self::assertSame('carga_horaria', $componentes[0]->campo);
        self::assertSame('90.000000', $componentes[0]->divisor); // Che

        self::assertSame('proporcion', $componentes[1]->tipo);
        self::assertSame('estudiantes_inscriptos', $componentes[1]->campo);
        self::assertSame('90.000000', $componentes[1]->divisor); // Cee

        self::assertSame('lookup', $componentes[2]->tipo);
        self::assertSame('tipo_espacio', $componentes[2]->campoClave);
        self::assertSame(
            ['1' => '2.000000', '2' => '3.000000', '3' => '5.000000', '4' => '12.000000'],
            $componentes[2]->tablaValoracion,
        );
    }

    public function test_umbral_de_ad3_jtp_ds(): void
    {
        self::assertSame(
            ['minimo' => '1.000000', 'superior' => '5.000000'],
            indice_repositorio::umbral('AD3', 'JTP', 'DS', self::ANIO),
        );
    }

    /**
     * Punta a punta: item 1 (Teórica, 45/90 carga x 45/90 inscriptos x 2) =
     * 0.5, item 2 (Teórico-Práctica, 90/90 x 180/90 x 3) = 6. PD3 = 6.5,
     * supera el superior (5) de JTP/DS -> AD3 = 3.
     */
    public function test_calculo_real_de_ad3_de_punta_a_punta(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearEspacio($ficha_id, tipo_esp_curricular_id: 1, carga_horario: 45, cant_inscriptos: 45);
        $this->crearEspacio($ficha_id, tipo_esp_curricular_id: 2, carga_horario: 90, cant_inscriptos: 180);

        $items = indice_repositorio::items_espacios_curriculares(self::LEGAJO, self::ANIO);
        $componentes = indice_repositorio::componentes('AD3', self::ANIO);
        $umbral = indice_repositorio::umbral('AD3', 'JTP', 'DS', self::ANIO);

        $evaluador = new \Pruebas\Indice\Motor\EvaluadorComponentes();
        $puntaje = '0';
        foreach ($items as $item) {
            $puntaje = bcadd($puntaje, $evaluador->evaluarItem($componentes, $item), 6);
        }

        self::assertSame('6.500000', $puntaje);
        self::assertSame(
            3,
            (new \Pruebas\Indice\Motor\Categorizador())->categorizar($puntaje, $umbral['minimo'], $umbral['superior']),
        );
    }

    private function crearFicha(): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_ad3', :anio, 4, 3)
             RETURNING id",
            ['dni' => self::DNI, 'legajo' => self::LEGAJO, 'anio' => self::ANIO],
        );
        return (int) $fila[0]['id'];
    }

    private function crearEspacio(int $ficha_id, int $tipo_esp_curricular_id, ?int $carga_horario, ?int $cant_inscriptos): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.c33_docec_facultad (ficha_id, tipo_esp_curricular_id, carga_horario, cant_inscriptos)
             VALUES (:ficha_id, :tipo, :carga, :inscriptos)',
            [
                'ficha_id' => $ficha_id,
                'tipo' => $tipo_esp_curricular_id,
                'carga' => $carga_horario,
                'inscriptos' => $cant_inscriptos,
            ],
        );
    }

    private function limpiar(): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.c33_docec_facultad
              WHERE ficha_id IN (SELECT id FROM public.ficha WHERE legajo_doc = :legajo)',
            ['legajo' => self::LEGAJO],
        );
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.ficha WHERE legajo_doc = :legajo',
            ['legajo' => self::LEGAJO],
        );
    }
}
