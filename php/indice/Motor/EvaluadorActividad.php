<?php

declare(strict_types=1);

namespace Pruebas\Indice\Motor;

use Pruebas\Indice\Dto\ResultadoActividad;

/**
 * Cadena completa (docs/indice/modelo-de-calculo.md, #7 y #8):
 *   ítems -> puntaje por ítem -> PD de dimensión -> valoración 0/1/3
 *   -> puntaje de la actividad -> umbral -> índice parcial e índice acumulado
 * a lo largo de un período de k años.
 *
 * indice_parcial e indice_acumulado se calculan con fc/fck en precisión
 * completa (CorreccionTemporal::calcular()['fc_completo'/'fck_completo']):
 * redondear antes de dividir/multiplicar corre el resultado del 6º decimal
 * en adelante (ver CorreccionTemporalTest).
 */
final class EvaluadorActividad
{
    private EvaluadorDimension $evaluadorDimension;
    private CorreccionTemporal $correccionTemporal;

    public function __construct(?EvaluadorDimension $evaluadorDimension = null, ?CorreccionTemporal $correccionTemporal = null)
    {
        $this->evaluadorDimension = $evaluadorDimension ?? new EvaluadorDimension();
        $this->correccionTemporal = $correccionTemporal ?? new CorreccionTemporal();
    }

    /**
     * @param array<string, array{componentes: \Pruebas\Indice\Dto\Componente[], umbral: array<string, array{minimo: string, superior: string}>}> $dimensiones
     *        config por dimensión: sus componentes y su umbral por "categoria|dedicacion"
     * @param list<array{k: int, categoria: string, dedicacion: string, lic_con_goce: string, lic_sin_goce: string}> $periodo
     *        ordenado por k ascendente
     * @param array<string, list<array<int, array<string, mixed>>>> $items
     *        [codigo_dimension][k-1] => ítems declarados ese año
     * @return array{dimensiones: array<string, list<\Pruebas\Indice\Dto\ResultadoDimension>>, actividad: list<ResultadoActividad>}
     */
    public function evaluar(string $actividad, array $dimensiones, array $periodo, array $items): array
    {
        $serieTemporal = $this->correccionTemporal->calcular(array_map(
            static fn (array $p) => [
                'k' => $p['k'],
                'lic_con_goce' => $p['lic_con_goce'],
                'lic_sin_goce' => $p['lic_sin_goce'],
            ],
            $periodo,
        ));

        $resultadosDimension = [];
        $resultadosActividad = [];
        $sumaPuntaje = '0';
        $sumaUmbral = '0';

        foreach ($periodo as $i => $anioInfo) {
            $claveCargo = $anioInfo['categoria'] . '|' . $anioInfo['dedicacion'];

            $puntajeActividad = '0.000000';
            $umbralActividad = '0.000000';

            foreach ($dimensiones as $codigo => $config) {
                $umbral = $config['umbral'][$claveCargo];
                $itemsAnio = $items[$codigo][$i] ?? [];

                $resultado = $this->evaluadorDimension->evaluar(
                    dimension: $codigo,
                    anio: $anioInfo['k'],
                    componentes: $config['componentes'],
                    items: $itemsAnio,
                    minimo: $umbral['minimo'],
                    superior: $umbral['superior'],
                );

                $resultadosDimension[$codigo][] = $resultado;
                $puntajeActividad = bcadd($puntajeActividad, $resultado->puntaje, 6);
                $umbralActividad = bcadd($umbralActividad, $umbral['minimo'], 6);
            }

            $sumaPuntaje = Bc::add($sumaPuntaje, $puntajeActividad);
            $sumaUmbral = Bc::add($sumaUmbral, $umbralActividad);

            $fcCompleto = $serieTemporal[$i]['fc_completo'];
            $fckCompleto = $serieTemporal[$i]['fck_completo'];

            $indiceParcial = Bc::round(Bc::div(Bc::div($puntajeActividad, $umbralActividad), $fcCompleto), 6);
            $indiceAcumulado = Bc::round(Bc::mul(Bc::div($sumaPuntaje, $sumaUmbral), $fckCompleto), 6);

            $resultadosActividad[] = new ResultadoActividad(
                actividad: $actividad,
                anio: $anioInfo['k'],
                puntaje: $puntajeActividad,
                umbral: $umbralActividad,
                indiceParcial: $indiceParcial,
                indiceAcumulado: $indiceAcumulado,
            );
        }

        return ['dimensiones' => $resultadosDimension, 'actividad' => $resultadosActividad];
    }
}
