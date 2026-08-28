<?php

declare(strict_types=1);

namespace Pruebas\Indice\Motor;

use Pruebas\Indice\Dto\Componente;
use Pruebas\Indice\Dto\ItemDeclarado;
use Pruebas\Indice\Dto\ResultadoDimension;

/**
 * ítems -> puntaje por ítem -> PD de la dimensión -> valoración 0/1/3
 * (docs/indice/modelo-de-calculo.md, #8). Un año, una dimensión.
 */
final class EvaluadorDimension
{
    private EvaluadorComponentes $evaluadorComponentes;
    private Categorizador $categorizador;

    public function __construct(?EvaluadorComponentes $evaluadorComponentes = null, ?Categorizador $categorizador = null)
    {
        $this->evaluadorComponentes = $evaluadorComponentes ?? new EvaluadorComponentes();
        $this->categorizador = $categorizador ?? new Categorizador();
    }

    /**
     * @param Componente[] $componentes
     * @param array<int, array<string, mixed>> $items ítems declarados por el docente en esa dimensión/año
     */
    public function evaluar(
        string $dimension,
        int $anio,
        array $componentes,
        array $items,
        string $minimo,
        string $superior,
    ): ResultadoDimension {
        $puntaje = '0.000000';
        foreach ($items as $campos) {
            $puntajeItem = $this->evaluadorComponentes->evaluarItem($componentes, new ItemDeclarado($campos));
            $puntaje = bcadd($puntaje, $puntajeItem, 6);
        }

        $valoracion = $this->categorizador->categorizar($puntaje, $minimo, $superior);

        return new ResultadoDimension($dimension, $anio, $puntaje, $valoracion, $minimo, $superior);
    }
}
