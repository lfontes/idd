<?php

declare(strict_types=1);

namespace Pruebas\Indice\Dto;

/**
 * Un ítem tal como lo declaró el docente en la ficha, ya traducido a los
 * nombres de campo que consumen los componentes (ver 02_siembra_docencia.sql).
 * El motor sólo usa `campos` (valor()/tiene()); `descripcion`, `origenTabla`,
 * `origenId` y `detalle` son trazabilidad para cuando exista la persistencia
 * (espejan evaluacion_item.descripcion/origen_tabla/origen_id/detalle) — el
 * motor no las necesita ni las mira.
 */
final class ItemDeclarado
{
    /**
     * @param array<string, string|int|null> $campos
     * @param array<string, mixed> $detalle
     */
    public function __construct(
        private readonly array $campos,
        public readonly ?string $descripcion = null,
        public readonly ?string $origenTabla = null,
        public readonly int|string|null $origenId = null,
        private readonly array $detalle = [],
    ) {
    }

    public function tiene(string $campo): bool
    {
        return isset($this->campos[$campo]);
    }

    /** @return array<string, string|int|null> */
    public function campos(): array
    {
        return $this->campos;
    }

    public function valor(string $campo): string|int|null
    {
        return $this->campos[$campo] ?? null;
    }

    /** @return array<string, mixed> */
    public function detalle(): array
    {
        return $this->detalle;
    }
}
