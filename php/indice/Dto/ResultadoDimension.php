<?php

declare(strict_types=1);

namespace Pruebas\Indice\Dto;

/**
 * Espejo de una fila de `evaluacion_dimension` (sql/indice/01_indice_ddl.sql):
 * el puntaje (PD) de una dimensión en un año, ya categorizado, con los
 * umbrales aplicados copiados para trazabilidad.
 */
final class ResultadoDimension
{
    public function __construct(
        public readonly string $dimension,
        public readonly int $anio,
        public readonly string $puntaje,
        public readonly int $valoracion,
        public readonly string $minimo,
        public readonly string $superior,
    ) {
    }
}
