<?php

declare(strict_types=1);

namespace Pruebas\Indice\Test\Motor;

use Pruebas\Indice\Motor\Categorizador;
use PHPUnit\Framework\TestCase;

/**
 * La regla es una sola para las catorce dimensiones (ver docs/indice/modelo-de-calculo.md, #2):
 *   PD < minimo               -> 0
 *   minimo <= PD <= superior  -> 1
 *   PD > superior             -> 3
 * En investigación superior = minimo; en docencia se separan. Los casos de
 * AD1 salen de docs/indice/fixtures/caso_docencia.json (categoria/PD1/valoracion
 * esperada) con los umbrales sembrados en sql/indice/02_siembra_docencia.sql
 * (AD1: T/AS/AJ -> 6/8).
 */
final class CategorizadorTest extends TestCase
{
    private Categorizador $categorizador;

    protected function setUp(): void
    {
        $this->categorizador = new Categorizador();
    }

    /** @dataProvider casosLimite */
    public function test_categoriza_segun_minimo_y_superior(string $puntaje, string $minimo, string $superior, int $esperado): void
    {
        self::assertSame($esperado, $this->categorizador->categorizar($puntaje, $minimo, $superior));
    }

    public static function casosLimite(): iterable
    {
        yield 'debajo del minimo -> 0' => ['5.999999', '6', '8', 0];
        yield 'exactamente en el minimo -> 1' => ['6', '6', '8', 1];
        yield 'entre minimo y superior -> 1' => ['7', '6', '8', 1];
        yield 'exactamente en el superior -> 1' => ['8', '6', '8', 1];
        yield 'apenas por encima del superior -> 3' => ['8.000001', '6', '8', 3];
        yield 'muy por encima -> 3' => ['100', '6', '8', 3];
        yield 'cero, minimo cero -> 1 (AY2 en AD1)' => ['0', '0', '2', 1];
        // investigación: superior = minimo (docs/indice/modelo-de-calculo.md, #3)
        yield 'superior = minimo, por debajo -> 0' => ['4', '5', '5', 0];
        yield 'superior = minimo, igual -> 1' => ['5', '5', '5', 1];
        yield 'superior = minimo, por encima -> 3' => ['5.000001', '5', '5', 3];
    }

    /**
     * Caso real: docente AJ/SE año 1 de docs/indice/fixtures/caso_docencia.json,
     * PD1 = 6, umbral AD1 para AJ = minimo 6 / superior 8 -> valoracion esperada 1.
     */
    public function test_caso_real_ad1_anio1_del_fixture(): void
    {
        self::assertSame(1, $this->categorizador->categorizar('6.000000', '6', '8'));
    }

    /**
     * Caso real: docente T/EX año 4 del fixture, PD1 = 14.5, umbral AD1 para T = 6/8
     * -> supera el superior -> valoracion esperada 3.
     */
    public function test_caso_real_ad1_anio4_del_fixture(): void
    {
        self::assertSame(3, $this->categorizador->categorizar('14.500000', '6', '8'));
    }
}
