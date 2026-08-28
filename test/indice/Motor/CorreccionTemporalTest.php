<?php

declare(strict_types=1);

namespace Pruebas\Indice\Test\Motor;

use Pruebas\Indice\Motor\CorreccionTemporal;
use PHPUnit\Framework\TestCase;

/**
 * docs/indice/modelo-de-calculo.md, #6:
 *   fc  = 1 - (licencia_con_goce + licencia_sin_goce) / 12
 *   fck = k / SUMA(fc de los años 1..k)
 * Se calcula una vez por evaluación (no por dimensión): "serie" recibe los k
 * años ordenados y devuelve fc/fck de cada uno. fck es acumulado -> depende
 * de los años anteriores, no sólo del propio. Los valores esperados son los
 * de docs/indice/fixtures/caso_docencia.json (periodo), tomados de la planilla.
 */
final class CorreccionTemporalTest extends TestCase
{
    private CorreccionTemporal $correccion;
    private static array $periodoFixture;

    public static function setUpBeforeClass(): void
    {
        $ruta = __DIR__ . '/../../../docs/indice/fixtures/caso_docencia.json';
        $fixture = json_decode((string) file_get_contents($ruta), true, flags: JSON_THROW_ON_ERROR);
        self::$periodoFixture = $fixture['periodo'];
    }

    protected function setUp(): void
    {
        $this->correccion = new CorreccionTemporal();
    }

    public function test_fc_de_un_solo_anio_sin_licencias(): void
    {
        $resultado = $this->correccion->calcular([
            ['k' => 1, 'lic_con_goce' => '0', 'lic_sin_goce' => '0'],
        ]);
        self::assertSame('1.000000', $resultado[0]['fc']);
        self::assertSame('1.000000', $resultado[0]['fck']);
    }

    public function test_fc_descuenta_las_licencias_sobre_12_meses(): void
    {
        // 6 meses de licencia con goce -> fc = 1 - 6/12 = 0.5
        $resultado = $this->correccion->calcular([
            ['k' => 1, 'lic_con_goce' => '6', 'lic_sin_goce' => '0'],
        ]);
        self::assertSame('0.500000', $resultado[0]['fc']);
    }

    public function test_serie_completa_del_fixture(): void
    {
        $entrada = array_map(
            static fn (array $p) => [
                'k' => $p['k'],
                'lic_con_goce' => $p['lic_con_goce'],
                'lic_sin_goce' => $p['lic_sin_goce'],
            ],
            self::$periodoFixture,
        );

        $resultado = $this->correccion->calcular($entrada);

        self::assertCount(4, $resultado);
        foreach (self::$periodoFixture as $i => $esperado) {
            self::assertSame($esperado['fc'], $resultado[$i]['fc'], "fc año {$esperado['k']}");
            self::assertSame($esperado['fck'], $resultado[$i]['fck'], "fck año {$esperado['k']}");
        }
    }

    /**
     * fck depende de la serie completa hasta ese año, no sólo del fc propio:
     * año 1 (AJ/SE, 5+3 meses de licencia) da fc chico pero fck = 3 exacto
     * (1 / (1/3)); si se calcula con el fc ya redondeado a 6 decimales en vez
     * de precisión completa, da 3.000003 en lugar de 3.000000 (ver fixture).
     */
    public function test_fck_usa_precision_completa_no_el_fc_redondeado(): void
    {
        $resultado = $this->correccion->calcular([
            ['k' => 1, 'lic_con_goce' => '5', 'lic_sin_goce' => '3'],
        ]);
        self::assertSame('3.000000', $resultado[0]['fck']);
    }

    /**
     * Además de fc/fck redondeados (lo que se persiste), calcular() expone la
     * versión en precisión completa: la orquestación por actividad los necesita
     * para indice_parcial/indice_acumulado, que también se corren de resultado
     * si se calculan con el fc/fck ya redondeado (mismo motivo que el test
     * anterior, pero un escalón más arriba en la cadena).
     */
    public function test_expone_fc_y_fck_en_precision_completa(): void
    {
        $resultado = $this->correccion->calcular([
            ['k' => 1, 'lic_con_goce' => '5', 'lic_sin_goce' => '3'],
            ['k' => 2, 'lic_con_goce' => '3', 'lic_sin_goce' => '0'],
        ]);

        // fc1 = 1 - 8/12 = 1/3: en precisión completa trae más dígitos que la
        // versión redondeada, y está a menos de una millonésima de ella (la
        // diferencia es sólo ruido de truncamiento en el último dígito interno).
        self::assertGreaterThan(strlen($resultado[0]['fc']), strlen($resultado[0]['fc_completo']));
        $this->assertAproximadamenteIgual($resultado[0]['fc'], $resultado[0]['fc_completo']);
        $this->assertAproximadamenteIgual($resultado[0]['fck'], $resultado[0]['fck_completo']);

        // fck2 = 2 / (1/3 + 0.75) = 1.846153846...
        self::assertStringStartsWith('1.84615384615384', $resultado[1]['fck_completo']);
    }

    private function assertAproximadamenteIgual(string $esperado, string $real, string $tolerancia = '0.000001'): void
    {
        $diferencia = ltrim(bcsub($esperado, $real, 10), '-');
        self::assertLessThan($tolerancia, $diferencia);
    }
}
