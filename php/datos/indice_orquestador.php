<?php

require_once __DIR__ . '/indice_repositorio.php';

/**
 * Compone indice_repositorio (ítems + config real, toba::db()) con el motor
 * puro (php/indice/Motor) para calcular una dimensión de un docente en un
 * año. Genérico desde que hay más de un proveedor de ítems implementado
 * (indice_repositorio::items() despacha por indice.dimension.proveedor):
 * agregar una dimensión nueva es agregar su proveedor en indice_repositorio,
 * no tocar esta clase.
 */
class indice_orquestador
{
    /**
     * Una dimensión, para un docente y año puntuales. null si el docente no
     * presentó ficha ese año: sin ficha no hay categoría/dedicación contra
     * la que categorizar, así que no hay ResultadoDimension posible (distinto
     * de puntaje=0, que sí es un resultado válido cuando hay ficha pero no
     * declaró nada). No persiste nada.
     */
    static function calcularDimension(string $codigo, int $legajo, int $anio): ?\Pruebas\Indice\Dto\ResultadoDimension
    {
        return self::contexto($codigo, $legajo, $anio)['resultado'] ?? null;
    }

    /**
     * Calcula una dimensión y la persiste (indice_repositorio::guardarEvaluacionDimension()):
     * evaluacion + evaluacion_dimension + evaluacion_item de ese año. No
     * arma evaluacion_periodo/evaluacion_actividad/evaluacion_indice —
     * período de k años pendiente. null si el docente no presentó ficha ese
     * año (nada que guardar).
     */
    static function guardarDimension(string $codigo, int $legajo, int $anio): ?\Pruebas\Indice\Dto\ResultadoDimension
    {
        $contexto = self::contexto($codigo, $legajo, $anio);
        if ($contexto === null) {
            return null;
        }

        indice_repositorio::guardarEvaluacionDimension(
            $legajo,
            $anio,
            $codigo,
            $contexto['resultado'],
            $contexto['itemsConPuntaje'],
        );

        return $contexto['resultado'];
    }

    /** @deprecated usar calcularDimension('AD1', ...) */
    static function calcularAD1(int $legajo, int $anio): ?\Pruebas\Indice\Dto\ResultadoDimension
    {
        return self::calcularDimension('AD1', $legajo, $anio);
    }

    /** @deprecated usar guardarDimension('AD1', ...) */
    static function guardarAD1(int $legajo, int $anio): ?\Pruebas\Indice\Dto\ResultadoDimension
    {
        return self::guardarDimension('AD1', $legajo, $anio);
    }

    /**
     * @return array{resultado: \Pruebas\Indice\Dto\ResultadoDimension, itemsConPuntaje: list<array{item: \Pruebas\Indice\Dto\ItemDeclarado, puntaje: string}>}|null
     */
    private static function contexto(string $codigo, int $legajo, int $anio): ?array
    {
        $cargo = indice_repositorio::categoria_dedicacion($legajo, $anio);
        if ($cargo === null) {
            return null;
        }

        $proveedor = indice_repositorio::proveedor($codigo, $anio);
        $itemsDeclarados = indice_repositorio::items($proveedor, $legajo, $anio);
        $componentes = indice_repositorio::componentes($codigo, $anio);
        $umbral = indice_repositorio::umbral($codigo, $cargo['categoria'], $cargo['dedicacion'], $anio);

        $evaluadorComponentes = new \Pruebas\Indice\Motor\EvaluadorComponentes();
        $itemsConPuntaje = array_map(
            static fn (\Pruebas\Indice\Dto\ItemDeclarado $item) => [
                'item' => $item,
                'puntaje' => $evaluadorComponentes->evaluarItem($componentes, $item),
            ],
            $itemsDeclarados,
        );

        $resultado = (new \Pruebas\Indice\Motor\EvaluadorDimension($evaluadorComponentes))->evaluar(
            dimension: $codigo,
            anio: $anio,
            componentes: $componentes,
            items: array_map(static fn (\Pruebas\Indice\Dto\ItemDeclarado $i) => $i->campos(), $itemsDeclarados),
            minimo: $umbral['minimo'],
            superior: $umbral['superior'],
        );

        return ['resultado' => $resultado, 'itemsConPuntaje' => $itemsConPuntaje];
    }
}
