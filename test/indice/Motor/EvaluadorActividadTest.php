<?php

declare(strict_types=1);

namespace Pruebas\Indice\Test\Motor;

use Pruebas\Indice\Motor\EvaluadorActividad;
use Pruebas\Indice\Test\Apoyo\ConfiguracionAD;
use PHPUnit\Framework\TestCase;

/**
 * Cadena completa (docs/indice/modelo-de-calculo.md, #7 y #8), para toda la
 * actividad AD a lo largo del período de 4 años del fixture:
 *   ítems -> puntaje por ítem -> PD de dimensión -> valoración 0/1/3
 *   -> puntaje de la actividad -> umbral -> índice parcial e índice acumulado
 *
 * indice_parcial   = puntaje / umbral / fc
 * indice_acumulado = SUMA(puntaje 1..k) / SUMA(umbral 1..k) x fck
 * Los dos usan fc/fck en precisión completa (no el valor ya redondeado a
 * 6 decimales que se persiste) — ver CorreccionTemporalTest, el motivo es el
 * mismo: redondear antes de dividir/multiplicar corre el resultado.
 */
final class EvaluadorActividadTest extends TestCase
{
    private EvaluadorActividad $evaluador;

    protected function setUp(): void
    {
        $this->evaluador = new EvaluadorActividad();
    }

    public function test_reproduce_puntaje_umbral_e_indices_de_los_cuatro_anios_del_fixture(): void
    {
        $fixture = ConfiguracionAD::fixture();

        $dimensiones = [];
        foreach (ConfiguracionAD::componentes() as $codigo => $componentes) {
            $dimensiones[$codigo] = [
                'componentes' => $componentes,
                'umbral' => ConfiguracionAD::umbral()[$codigo],
            ];
        }

        $resultado = $this->evaluador->evaluar(
            actividad: 'AD',
            dimensiones: $dimensiones,
            periodo: $fixture['periodo'],
            items: $fixture['items'],
        );

        self::assertCount(4, $resultado['actividad']);

        foreach ($fixture['esperado']['actividad'] as $i => $esperado) {
            $real = $resultado['actividad'][$i];

            self::assertSame($esperado['k'], $real->anio, "año {$esperado['k']}: anio");
            self::assertSame('AD', $real->actividad, "año {$esperado['k']}: actividad");
            self::assertSame($esperado['puntaje'], $real->puntaje, "año {$esperado['k']}: puntaje");
            self::assertSame($esperado['umbral'], $real->umbral, "año {$esperado['k']}: umbral");
            self::assertSame($esperado['indice_parcial'], $real->indiceParcial, "año {$esperado['k']}: indice_parcial");
            self::assertSame($esperado['indice_acumulado'], $real->indiceAcumulado, "año {$esperado['k']}: indice_acumulado");
        }
    }

    /**
     * Trazabilidad (docs/indice/modelo-de-calculo.md, #8): también se pueden
     * pedir los resultados por dimensión, no sólo el agregado de la actividad.
     */
    public function test_expone_tambien_los_resultados_por_dimension(): void
    {
        $fixture = ConfiguracionAD::fixture();

        $dimensiones = [];
        foreach (ConfiguracionAD::componentes() as $codigo => $componentes) {
            $dimensiones[$codigo] = [
                'componentes' => $componentes,
                'umbral' => ConfiguracionAD::umbral()[$codigo],
            ];
        }

        $resultado = $this->evaluador->evaluar('AD', $dimensiones, $fixture['periodo'], $fixture['items']);

        self::assertSame(
            $fixture['esperado']['dimensiones']['AD3']['pd'][2],
            $resultado['dimensiones']['AD3'][2]->puntaje,
            'AD3 año 3: puntaje',
        );
        self::assertSame(
            $fixture['esperado']['dimensiones']['AD3']['valoracion'][2],
            $resultado['dimensiones']['AD3'][2]->valoracion,
            'AD3 año 3: valoracion',
        );
    }
}
