<?php

declare(strict_types=1);

namespace Pruebas\Indice\Test\Motor;

use Pruebas\Indice\Dto\Componente;
use Pruebas\Indice\Dto\ItemDeclarado;
use Pruebas\Indice\Motor\EvaluadorComponentes;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * "puntaje del ítem = SUMA sobre términos (PRODUCTO de los componentes del
 * término)" (docs/indice/modelo-de-calculo.md, #4). Los componentes de AD1..AD8
 * son los mismos que sql/indice/02_siembra_docencia.sql; los items y los
 * puntajes esperados salen de docs/indice/fixtures/caso_docencia.json.
 * banderas_excluyentes/banderas_acumulativas no están sembrados en docencia
 * (los usa investigación, que todavía no existe) así que sus casos son
 * sintéticos, fieles al catálogo descripto en el modelo.
 */
final class EvaluadorComponentesTest extends TestCase
{
    private EvaluadorComponentes $evaluador;
    private static array $fixture;

    public static function setUpBeforeClass(): void
    {
        $ruta = __DIR__ . '/../../../docs/indice/fixtures/caso_docencia.json';
        self::$fixture = json_decode((string) file_get_contents($ruta), true, flags: JSON_THROW_ON_ERROR);
    }

    protected function setUp(): void
    {
        $this->evaluador = new EvaluadorComponentes();
    }

    private function itemsFixture(string $dimension, int $anio): array
    {
        // anio es 1-indexado, igual que 'k' en el fixture
        return self::$fixture['items'][$dimension][$anio - 1];
    }

    private function pdEsperado(string $dimension, int $anio): string
    {
        return self::$fixture['esperado']['dimensiones'][$dimension]['pd'][$anio - 1];
    }

    /** @param Componente[] $componentes */
    private function sumaDimension(array $componentes, array $items): string
    {
        $total = '0.000000';
        foreach ($items as $campos) {
            $total = bcadd($total, $this->evaluador->evaluarItem($componentes, new ItemDeclarado($campos)), 6);
        }
        return $total;
    }

    // ------------------------------------------------------------------
    // AD1: producto (cantidad x valor del cuadro)
    // ------------------------------------------------------------------
    public function test_ad1_producto_formacion_academica(): void
    {
        $componentes = [
            new Componente(
                tipo: 'producto',
                termino: 1,
                campo: 'cantidad',
                campoClave: 'tipo_formacion',
                tablaValoracion: [
                    'Pregrado' => '1', 'Grado' => '3', 'Posdoctorado' => '6', 'Doctorado' => '5',
                    'Maestría' => '3', 'Especialización' => '1', 'Diplomado' => '0.5',
                ],
            ),
        ];

        // año 4: Grado(1) + Doctorado(1) + Maestría(2) + Diplomado(1) = 3+5+6+0.5 = 14.5
        $items = $this->itemsFixture('AD1', 4);
        self::assertSame('3.000000', $this->evaluador->evaluarItem($componentes, new ItemDeclarado($items[0])));
        self::assertSame('5.000000', $this->evaluador->evaluarItem($componentes, new ItemDeclarado($items[1])));

        foreach ([1, 2, 3, 4] as $anio) {
            self::assertSame(
                $this->pdEsperado('AD1', $anio),
                $this->sumaDimension($componentes, $this->itemsFixture('AD1', $anio)),
                "AD1 año {$anio}",
            );
        }
    }

    // ------------------------------------------------------------------
    // AD2: divisor_condicional (horas/divisor según banderas) + dos condicional
    // (pasantía, distinción) sumados en términos aparte
    // ------------------------------------------------------------------
    public function test_ad2_divisor_condicional_y_condicionales(): void
    {
        $componentes = [
            new Componente(
                tipo: 'divisor_condicional',
                termino: 1,
                campo: 'horas',
                params: [
                    'reglas' => [
                        ['cuando' => ['curso' => 1, 'aprobado' => 1], 'divisor' => 25],
                        ['cuando' => ['curso' => 1, 'asistencia' => 1], 'divisor' => 50],
                        ['cuando' => ['taller' => 1, 'aprobado' => 1], 'divisor' => 25],
                        ['cuando' => ['taller' => 1, 'asistencia' => 1], 'divisor' => 50],
                        ['cuando' => ['form_obligatoria' => 1, 'aprobado' => 1], 'divisor' => 25],
                    ],
                    'defecto' => 0,
                ],
            ),
            new Componente(tipo: 'condicional', termino: 2, campoClave: 'pasantia', constante: '5'),
            new Componente(tipo: 'condicional', termino: 3, campoClave: 'distincion', constante: '0.5'),
        ];

        foreach ([1, 2, 3, 4] as $anio) {
            self::assertSame(
                $this->pdEsperado('AD2', $anio),
                $this->sumaDimension($componentes, $this->itemsFixture('AD2', $anio)),
                "AD2 año {$anio}",
            );
        }
    }

    public function test_ad2_item_que_no_matchea_ninguna_regla_da_el_defecto(): void
    {
        $componente = new Componente(
            tipo: 'divisor_condicional',
            campo: 'horas',
            params: [
                'reglas' => [['cuando' => ['curso' => 1, 'aprobado' => 1], 'divisor' => 25]],
                'defecto' => 0,
            ],
        );
        $item = new ItemDeclarado(['horas' => '30', 'curso' => 0, 'aprobado' => 0]);
        self::assertSame('0.000000', $this->evaluador->evaluarItem([$componente], $item));
    }

    // ------------------------------------------------------------------
    // AD3: proporcion x proporcion x lookup, un solo término (multiplicativo)
    // ------------------------------------------------------------------
    public function test_ad3_proporcion_y_lookup_multiplicados(): void
    {
        $componentes = [
            new Componente(tipo: 'proporcion', termino: 1, campo: 'carga_horaria', divisor: '90'),
            new Componente(tipo: 'proporcion', termino: 1, campo: 'estudiantes_inscriptos', divisor: '90'),
            new Componente(
                tipo: 'lookup',
                termino: 1,
                campoClave: 'tipo_espacio',
                tablaValoracion: ['1' => '2', '2' => '3', '3' => '5', '4' => '12'],
            ),
        ];

        foreach ([1, 2, 3, 4] as $anio) {
            self::assertSame(
                $this->pdEsperado('AD3', $anio),
                $this->sumaDimension($componentes, $this->itemsFixture('AD3', $anio)),
                "AD3 año {$anio}",
            );
        }
    }

    // ------------------------------------------------------------------
    // AD4: directo x proporcion(/100) x lookup, un término
    // ------------------------------------------------------------------
    public function test_ad4_directo_proporcion_lookup(): void
    {
        $componentes = [
            new Componente(tipo: 'directo', termino: 1, campo: 'creditos'),
            new Componente(tipo: 'proporcion', termino: 1, campo: 'participacion', divisor: '100'),
            new Componente(
                tipo: 'lookup',
                termino: 1,
                campoClave: 'carrera',
                tablaValoracion: [
                    'Doctorado' => '7', 'Maestría' => '5', 'Especialización' => '3',
                    'Diplomatura' => '1', 'Diplomado' => '0.5',
                ],
            ),
        ];

        foreach ([1, 2, 3, 4] as $anio) {
            self::assertSame(
                $this->pdEsperado('AD4', $anio),
                $this->sumaDimension($componentes, $this->itemsFixture('AD4', $anio)),
                "AD4 año {$anio}",
            );
        }
    }

    // ------------------------------------------------------------------
    // AD5: lookup (participación) + lookup doble entrada (presentación x referato),
    // en términos distintos -> se suman. Los items sin presentación (Organización,
    // Evaluador) no declaran tipo_presentacion: ese término debe resolver a 0.
    // ------------------------------------------------------------------
    public function test_ad5_participacion_mas_presentacion(): void
    {
        $componentes = [
            new Componente(
                tipo: 'lookup',
                termino: 1,
                campoClave: 'tipo_participacion',
                tablaValoracion: ['Asistente' => '1', 'Expositor' => '3', 'Organización' => '4', 'Evaluador' => '5'],
            ),
            new Componente(
                tipo: 'lookup',
                termino: 2,
                campoClave: 'tipo_presentacion',
                campoClave2: 'referato',
                tablaValoracion: [
                    'Resumen' => ['0' => '1', '1' => '3'],
                    'Trabajo Extendido' => ['0' => '3', '1' => '5'],
                    'Trabajo Completo' => ['0' => '5', '1' => '8'],
                ],
            ),
        ];

        // año1, item 'SSE': Organización, sin presentación -> solo el término 1 aporta (4)
        $items = $this->itemsFixture('AD5', 1);
        $sse = current(array_filter($items, static fn (array $it) => $it['descripcion'] === 'SSE'));
        self::assertSame('4.000000', $this->evaluador->evaluarItem($componentes, new ItemDeclarado($sse)));

        foreach ([1, 2, 3, 4] as $anio) {
            self::assertSame(
                $this->pdEsperado('AD5', $anio),
                $this->sumaDimension($componentes, $this->itemsFixture('AD5', $anio)),
                "AD5 año {$anio}",
            );
        }
    }

    // ------------------------------------------------------------------
    // AD6 / AD7: un único lookup
    // ------------------------------------------------------------------
    public function test_ad6_lookup_participacion_en_proyecto(): void
    {
        $componentes = [
            new Componente(
                tipo: 'lookup',
                campoClave: 'tipo_participacion',
                tablaValoracion: ['Director' => '8', 'Codirector' => '5', 'Participante' => '1', 'Evaluador' => '5'],
            ),
        ];

        foreach ([1, 2, 3, 4] as $anio) {
            self::assertSame(
                $this->pdEsperado('AD6', $anio),
                $this->sumaDimension($componentes, $this->itemsFixture('AD6', $anio)),
                "AD6 año {$anio}",
            );
        }
    }

    public function test_ad7_lookup_categoria_rrhh(): void
    {
        $componentes = [
            new Componente(
                tipo: 'lookup',
                campoClave: 'categoria_rrhh',
                tablaValoracion: ['Docente' => '10', 'Adscripto' => '5', 'Pasante' => '3', 'Concurrencia' => '3'],
            ),
        ];

        foreach ([1, 2, 3, 4] as $anio) {
            self::assertSame(
                $this->pdEsperado('AD7', $anio),
                $this->sumaDimension($componentes, $this->itemsFixture('AD7', $anio)),
                "AD7 año {$anio}",
            );
        }
    }

    // ------------------------------------------------------------------
    // AD8: lookup x lookup, mismo término (multiplicativo: material x tarea)
    // ------------------------------------------------------------------
    public function test_ad8_material_por_tarea(): void
    {
        $componentes = [
            new Componente(
                tipo: 'lookup',
                termino: 1,
                campoClave: 'material',
                tablaValoracion: ['Apuntes de clase' => '8', 'Trabajo Práctico' => '5', 'Entorno Virtual' => '8'],
            ),
            new Componente(
                tipo: 'lookup',
                termino: 1,
                campoClave: 'tarea',
                tablaValoracion: ['Desarrollo' => '5', 'Actualización' => '1'],
            ),
        ];

        foreach ([1, 2, 3, 4] as $anio) {
            self::assertSame(
                $this->pdEsperado('AD8', $anio),
                $this->sumaDimension($componentes, $this->itemsFixture('AD8', $anio)),
                "AD8 año {$anio}",
            );
        }
    }

    // ------------------------------------------------------------------
    // Catálogo cerrado: banderas_excluyentes / banderas_acumulativas.
    // No están sembrados en docencia (los usa investigación, AI5/AI6):
    // casos sintéticos fieles al modelo, no vienen del fixture.
    // ------------------------------------------------------------------
    public function test_banderas_excluyentes_toma_la_unica_marcada(): void
    {
        $componente = new Componente(
            tipo: 'banderas_excluyentes',
            tablaValoracion: ['congreso' => '3', 'jornada' => '1', 'simposio' => '5'],
        );
        $item = new ItemDeclarado(['congreso' => 0, 'jornada' => 1, 'simposio' => 0]);
        self::assertSame('1.000000', $this->evaluador->evaluarItem([$componente], $item));
    }

    public function test_banderas_excluyentes_ninguna_marcada_da_cero(): void
    {
        $componente = new Componente(
            tipo: 'banderas_excluyentes',
            tablaValoracion: ['congreso' => '3', 'jornada' => '1'],
        );
        $item = new ItemDeclarado(['congreso' => 0, 'jornada' => 0]);
        self::assertSame('0.000000', $this->evaluador->evaluarItem([$componente], $item));
    }

    public function test_banderas_excluyentes_mas_de_una_marcada_es_un_error(): void
    {
        $componente = new Componente(
            tipo: 'banderas_excluyentes',
            tablaValoracion: ['congreso' => '3', 'jornada' => '1'],
        );
        $item = new ItemDeclarado(['congreso' => 1, 'jornada' => 1]);

        $this->expectException(RuntimeException::class);
        $this->evaluador->evaluarItem([$componente], $item);
    }

    public function test_banderas_acumulativas_suma_todas_las_marcadas(): void
    {
        $componente = new Componente(
            tipo: 'banderas_acumulativas',
            tablaValoracion: ['director' => '5', 'expositor' => '3', 'organizador' => '2'],
        );
        $item = new ItemDeclarado(['director' => 1, 'expositor' => 1, 'organizador' => 0]);
        self::assertSame('8.000000', $this->evaluador->evaluarItem([$componente], $item));
    }

    // ------------------------------------------------------------------
    // Robustez de lookup: campo no declarado -> 0 (ítem no aplica);
    // campo declarado pero con una clave que no está en el cuadro -> error
    // (protege contra normativa mal cargada, no contra un caso de negocio real).
    // ------------------------------------------------------------------
    public function test_lookup_con_clave_no_declarada_resuelve_cero(): void
    {
        $componente = new Componente(
            tipo: 'lookup',
            campoClave: 'tipo_presentacion',
            tablaValoracion: ['Resumen' => '1'],
        );
        $item = new ItemDeclarado(['tipo_participacion' => 'Organización']);
        self::assertSame('0.000000', $this->evaluador->evaluarItem([$componente], $item));
    }

    public function test_lookup_con_clave_declarada_pero_inexistente_en_el_cuadro_lanza_excepcion(): void
    {
        $componente = new Componente(
            tipo: 'lookup',
            campoClave: 'tipo_formacion',
            tablaValoracion: ['Grado' => '3'],
        );
        $item = new ItemDeclarado(['tipo_formacion' => 'Posgrado (typo)']);

        $this->expectException(RuntimeException::class);
        $this->evaluador->evaluarItem([$componente], $item);
    }
}
