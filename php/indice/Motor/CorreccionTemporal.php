<?php

declare(strict_types=1);

namespace Pruebas\Indice\Motor;

/**
 * docs/indice/modelo-de-calculo.md, #6:
 *   fc  = 1 - (licencia_con_goce + licencia_sin_goce) / 12
 *   fck = k / SUMA(fc de los años 1..k)
 * Se calcula una vez por evaluación, transversal a todas las actividades.
 * fck es acumulado: la suma de fc usa precisión completa, no los fc ya
 * redondeados a 6 decimales — redondear antes desvía el resultado (ver test
 * fck_usa_precision_completa_no_el_fc_redondeado).
 */
final class CorreccionTemporal
{
    /**
     * fc/fck vienen redondeados a 6 decimales (lo que se persiste, numeric(14,6))
     * y también en precisión completa (fc_completo/fck_completo): la
     * orquestación por actividad los necesita para no perder precisión al
     * calcular índices — ver test_fck_usa_precision_completa_no_el_fc_redondeado.
     *
     * @param list<array{k: int, lic_con_goce: string, lic_sin_goce: string}> $periodos ordenados por k ascendente
     * @return list<array{k: int, fc: string, fck: string, fc_completo: string, fck_completo: string}>
     */
    public function calcular(array $periodos): array
    {
        $resultado = [];
        $sumaFc = '0';

        foreach ($periodos as $periodo) {
            $fc = $this->fc($periodo['lic_con_goce'], $periodo['lic_sin_goce']);
            $sumaFc = Bc::add($sumaFc, $fc);
            $fck = Bc::div((string) $periodo['k'], $sumaFc);

            $resultado[] = [
                'k' => $periodo['k'],
                'fc' => Bc::round($fc, 6),
                'fck' => Bc::round($fck, 6),
                'fc_completo' => $fc,
                'fck_completo' => $fck,
            ];
        }

        return $resultado;
    }

    private function fc(string $licConGoce, string $licSinGoce): string
    {
        $licencias = Bc::add($licConGoce, $licSinGoce);
        return Bc::sub('1', Bc::div($licencias, '12'));
    }
}
