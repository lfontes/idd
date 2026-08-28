<?php

declare(strict_types=1);

namespace Pruebas\Indice\Test\Motor;

use Pruebas\Indice\Motor\EvaluadorDimension;
use Pruebas\Indice\Test\Apoyo\ConfiguracionAD;
use PHPUnit\Framework\TestCase;

/**
 * Orquesta un escalón de la cadena (docs/indice/modelo-de-calculo.md, #8):
 *   ítems -> puntaje por ítem (EvaluadorComponentes) -> PD de la dimensión
 *   -> valoración 0/1/3 (Categorizador)
 * Un año, una dimensión: suma el puntaje de todos los ítems declarados y
 * categoriza contra el umbral de esa dimensión/categoría/dedicación.
 */
final class EvaluadorDimensionTest extends TestCase
{
    private EvaluadorDimension $evaluador;

    protected function setUp(): void
    {
        $this->evaluador = new EvaluadorDimension();
    }

    public function test_ad1_anio1_docente_aj_pd_y_valoracion_del_fixture(): void
    {
        $fixture = ConfiguracionAD::fixture();
        $componentes = ConfiguracionAD::componentes()['AD1'];
        $umbral = ConfiguracionAD::umbral()['AD1']['AJ|SE']; // periodo[0]: categoria=AJ, dedicacion=SE

        $resultado = $this->evaluador->evaluar(
            dimension: 'AD1',
            anio: 1,
            componentes: $componentes,
            items: $fixture['items']['AD1'][0],
            minimo: $umbral['minimo'],
            superior: $umbral['superior'],
        );

        self::assertSame('AD1', $resultado->dimension);
        self::assertSame(1, $resultado->anio);
        self::assertSame($fixture['esperado']['dimensiones']['AD1']['pd'][0], $resultado->puntaje);
        self::assertSame($fixture['esperado']['dimensiones']['AD1']['valoracion'][0], $resultado->valoracion);
        self::assertSame('6', $resultado->minimo);
        self::assertSame('8', $resultado->superior);
    }

    /**
     * Recorre las ocho dimensiones en los cuatro años y compara PD y
     * valoración contra docs/indice/fixtures/caso_docencia.json completo.
     */
    public function test_las_ocho_dimensiones_en_los_cuatro_anios_del_fixture(): void
    {
        $fixture = ConfiguracionAD::fixture();
        $componentesPorDimension = ConfiguracionAD::componentes();
        $umbralPorDimension = ConfiguracionAD::umbral();

        foreach ($fixture['periodo'] as $i => $anioInfo) {
            $claveCargo = $anioInfo['categoria'] . '|' . $anioInfo['dedicacion'];

            foreach ($componentesPorDimension as $dimension => $componentes) {
                $umbral = $umbralPorDimension[$dimension][$claveCargo];

                $resultado = $this->evaluador->evaluar(
                    dimension: $dimension,
                    anio: $anioInfo['k'],
                    componentes: $componentes,
                    items: $fixture['items'][$dimension][$i],
                    minimo: $umbral['minimo'],
                    superior: $umbral['superior'],
                );

                self::assertSame(
                    $fixture['esperado']['dimensiones'][$dimension]['pd'][$i],
                    $resultado->puntaje,
                    "{$dimension} año {$anioInfo['k']}: puntaje",
                );
                self::assertSame(
                    $fixture['esperado']['dimensiones'][$dimension]['valoracion'][$i],
                    $resultado->valoracion,
                    "{$dimension} año {$anioInfo['k']}: valoracion",
                );
            }
        }
    }
}
