<?php

declare(strict_types=1);

namespace Pruebas\Indice\Motor;

/**
 * Aritmética exacta interna del motor (nunca float — CLAUDE.md, "Nunca float
 * para puntajes"). Las operaciones intermedias se llevan a escala alta para
 * no perder precisión antes de tiempo; el redondeo a la escala final
 * (numeric(14,6) en la base) se hace una sola vez, al final de cada cálculo.
 */
final class Bc
{
    private const ESCALA_INTERNA = 20;

    public static function add(string $a, string $b): string
    {
        return bcadd($a, $b, self::ESCALA_INTERNA);
    }

    public static function sub(string $a, string $b): string
    {
        return bcsub($a, $b, self::ESCALA_INTERNA);
    }

    public static function mul(string $a, string $b): string
    {
        return bcmul($a, $b, self::ESCALA_INTERNA);
    }

    public static function div(string $a, string $b): string
    {
        return bcdiv($a, $b, self::ESCALA_INTERNA);
    }

    /**
     * Redondeo half-up a $escala decimales. bcmath (PHP < 8.4) no trae
     * bcround(); floor(x + 0.5) sobre el valor desplazado hace lo mismo
     * sin pasar por float en ningún momento.
     */
    public static function round(string $numero, int $escala): string
    {
        $negativo = str_starts_with($numero, '-');
        if ($negativo) {
            $numero = substr($numero, 1);
        }

        $factor = bcpow('10', (string) $escala, 0);
        $desplazado = bcmul($numero, $factor, self::ESCALA_INTERNA);
        $entero = bcadd($desplazado, '0.5', 0);
        $resultado = bcdiv($entero, $factor, $escala);

        if ($negativo && bccomp($resultado, '0', $escala) !== 0) {
            $resultado = '-' . $resultado;
        }

        return $resultado;
    }
}
