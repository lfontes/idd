<?php

declare(strict_types=1);

namespace Pruebas\Indice\Dto;

/**
 * Espejo de una fila de `evaluacion_actividad` (sql/indice/01_indice_ddl.sql):
 * puntaje/umbral de la actividad en un año, más los dos índices
 * (docs/indice/modelo-de-calculo.md, #7):
 *   indice_parcial   = puntaje / umbral / fc
 *   indice_acumulado = SUMA(puntaje) / SUMA(umbral) x fck   (años 1..anio)
 */
final class ResultadoActividad
{
    public function __construct(
        public readonly string $actividad,
        public readonly int $anio,
        public readonly string $puntaje,
        public readonly string $umbral,
        public readonly string $indiceParcial,
        public readonly string $indiceAcumulado,
    ) {
    }
}
