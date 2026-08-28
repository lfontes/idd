<?php

declare(strict_types=1);

namespace Pruebas\Indice\Dto;

/**
 * Espejo de una fila de la tabla `componente` (sql/indice/01_indice_ddl.sql),
 * ya resuelta: `divisor` siempre trae el valor numérico final, sin importar
 * si en la normativa era un literal o un `divisor_parametro` con nombre
 * (Che, Cee...) — esa resolución es responsabilidad de quien arma el DTO,
 * no del motor.
 *
 * `tablaValoracion` representa el cuadro de valoración ya cargado:
 *   - cuadro simple:      [clave => valor]
 *   - cuadro doble entrada: [clave => [clave2 => valor]]
 */
final class Componente
{
    /**
     * @param array<string, mixed>|null $tablaValoracion
     * @param array<string, mixed>|null $params
     */
    public function __construct(
        public readonly string $tipo,
        public readonly int $termino = 1,
        public readonly ?string $campo = null,
        public readonly ?string $campoClave = null,
        public readonly ?string $campoClave2 = null,
        public readonly ?array $tablaValoracion = null,
        public readonly ?string $divisor = null,
        public readonly ?string $constante = null,
        public readonly ?array $params = null,
    ) {
    }
}
