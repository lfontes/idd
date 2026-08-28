<?php

declare(strict_types=1);

namespace Pruebas\Indice\Motor;

use Pruebas\Indice\Dto\Componente;
use Pruebas\Indice\Dto\ItemDeclarado;
use RuntimeException;

/**
 * puntaje del ítem = SUMA sobre términos (PRODUCTO de los componentes del
 * término) — docs/indice/modelo-de-calculo.md, #4. Catálogo cerrado de 8
 * tipos de componente (mismo catálogo que sql/indice/01_indice_ddl.sql).
 */
final class EvaluadorComponentes
{
    /** @param Componente[] $componentes */
    public function evaluarItem(array $componentes, ItemDeclarado $item): string
    {
        $porTermino = [];
        foreach ($componentes as $componente) {
            $porTermino[$componente->termino][] = $this->resolver($componente, $item);
        }

        $total = '0';
        foreach ($porTermino as $factores) {
            $producto = '1';
            foreach ($factores as $factor) {
                $producto = Bc::mul($producto, $factor);
            }
            $total = Bc::add($total, $producto);
        }

        return Bc::round($total, 6);
    }

    private function resolver(Componente $componente, ItemDeclarado $item): string
    {
        return match ($componente->tipo) {
            'lookup' => $this->lookup($componente, $item),
            'producto' => $this->producto($componente, $item),
            'proporcion' => $this->proporcion($componente, $item),
            'condicional' => $this->condicional($componente, $item),
            'divisor_condicional' => $this->divisorCondicional($componente, $item),
            'directo' => $this->directo($componente, $item),
            'banderas_excluyentes' => $this->banderasExcluyentes($componente, $item),
            'banderas_acumulativas' => $this->banderasAcumulativas($componente, $item),
            default => throw new RuntimeException("Tipo de componente desconocido: {$componente->tipo}"),
        };
    }

    private function lookup(Componente $componente, ItemDeclarado $item): string
    {
        $clave = $item->valor((string) $componente->campoClave);
        if ($clave === null) {
            return '0'; // el ítem no declaró este aspecto: no aplica, no es un error
        }

        $clave2 = $componente->campoClave2 !== null
            ? (string) ($item->valor($componente->campoClave2) ?? '')
            : '';

        $fila = $componente->tablaValoracion[(string) $clave] ?? null;
        if ($fila === null) {
            throw new RuntimeException("No hay valoración cargada para la clave '{$clave}'.");
        }

        if (is_array($fila)) {
            if (!array_key_exists($clave2, $fila)) {
                throw new RuntimeException("No hay valoración cargada para la clave '{$clave}' / '{$clave2}'.");
            }
            return (string) $fila[$clave2];
        }

        return (string) $fila;
    }

    private function producto(Componente $componente, ItemDeclarado $item): string
    {
        $cantidad = (string) ($item->valor((string) $componente->campo) ?? '0');
        return Bc::mul($cantidad, $this->lookup($componente, $item));
    }

    private function proporcion(Componente $componente, ItemDeclarado $item): string
    {
        $valor = (string) ($item->valor((string) $componente->campo) ?? '0');
        return Bc::div($valor, (string) $componente->divisor);
    }

    private function condicional(Componente $componente, ItemDeclarado $item): string
    {
        return $this->estaMarcado($item->valor((string) $componente->campoClave))
            ? (string) $componente->constante
            : '0';
    }

    private function divisorCondicional(Componente $componente, ItemDeclarado $item): string
    {
        $valor = (string) ($item->valor((string) $componente->campo) ?? '0');

        foreach ($componente->params['reglas'] ?? [] as $regla) {
            if ($this->coincide($regla['cuando'], $item)) {
                return Bc::div($valor, (string) $regla['divisor']);
            }
        }

        return (string) ($componente->params['defecto'] ?? '0');
    }

    private function directo(Componente $componente, ItemDeclarado $item): string
    {
        return (string) ($item->valor((string) $componente->campo) ?? '0');
    }

    private function banderasExcluyentes(Componente $componente, ItemDeclarado $item): string
    {
        $marcadas = [];
        foreach ($componente->tablaValoracion ?? [] as $clave => $valor) {
            if ($this->estaMarcado($item->valor((string) $clave))) {
                $marcadas[] = (string) $valor;
            }
        }

        if (count($marcadas) > 1) {
            throw new RuntimeException('Más de una bandera excluyente marcada en el mismo ítem.');
        }

        return $marcadas[0] ?? '0';
    }

    private function banderasAcumulativas(Componente $componente, ItemDeclarado $item): string
    {
        $total = '0';
        foreach ($componente->tablaValoracion ?? [] as $clave => $valor) {
            if ($this->estaMarcado($item->valor((string) $clave))) {
                $total = Bc::add($total, (string) $valor);
            }
        }
        return $total;
    }

    private function coincide(array $cuando, ItemDeclarado $item): bool
    {
        foreach ($cuando as $campo => $esperado) {
            if ((int) ($item->valor((string) $campo) ?? 0) !== (int) $esperado) {
                return false;
            }
        }
        return true;
    }

    private function estaMarcado(string|int|null $valor): bool
    {
        return $valor !== null && (int) $valor === 1;
    }
}
