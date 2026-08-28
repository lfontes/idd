<?php

declare(strict_types=1);

namespace Pruebas\Indice\Motor;

/**
 * Regla única de categorización (docs/indice/modelo-de-calculo.md, #2):
 *   PD < minimo               -> 0
 *   minimo <= PD <= superior  -> 1
 *   PD > superior             -> 3
 * Vale para las catorce dimensiones sin excepciones; en investigación
 * superior = minimo, en docencia se separan.
 */
final class Categorizador
{
    public function categorizar(string $puntaje, string $minimo, string $superior): int
    {
        if (bccomp($puntaje, $minimo, 6) < 0) {
            return 0;
        }

        if (bccomp($puntaje, $superior, 6) <= 0) {
            return 1;
        }

        return 3;
    }
}
