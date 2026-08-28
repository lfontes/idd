<?php

require_once __DIR__ . '/indice_repositorio.php';

/**
 * Compone indice_repositorio (ítems + config real, toba::db()) con el motor
 * puro (php/indice/Motor) para calcular una dimensión de un docente en un
 * año. Genérico desde que hay más de un proveedor de ítems implementado
 * (indice_repositorio::items() despacha por indice.dimension.proveedor):
 * agregar una dimensión nueva es agregar su proveedor en indice_repositorio,
 * no tocar esta clase.
 *
 * cerrarPeriodo() es independiente de calcularDimension()/guardarDimension():
 * arma evaluacion_periodo (el enlace ficha<->evaluación con fc/fck) sin
 * calcular ninguna dimensión. Ambos flujos comparten la misma evaluación
 * "provisorio" (indice_repositorio::evaluacion_abierta()).
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
     * Cierra el período de un docente: un renglón de evaluacion_periodo por
     * cada año con ficha (indice_repositorio::fichas()), con categoría/
     * dedicación de ese año y fc/fck calculados por CorreccionTemporal sobre
     * la serie completa (fck depende de los años anteriores, no sólo del
     * propio — docs/indice/modelo-de-calculo.md #6). No calcula ni persiste
     * dimensiones (eso es guardarDimension(), independiente): esto sólo arma
     * el enlace ficha<->evaluación que necesita indice.vista_idd.
     *
     * Sólo cubre años efectivamente presentados: un año sin ficha no genera
     * renglón (docs/indice/decisiones-pendientes.md, A5, sigue sin resolver
     * para el caso de "año faltante que sí debería computar cero").
     *
     * No hace nada si el docente no tiene ninguna ficha.
     */
    static function cerrarPeriodo(int $legajo): void
    {
        $fichas = indice_repositorio::fichas($legajo);
        if ($fichas === []) {
            return;
        }

        $periodos = [];
        foreach ($fichas as $k => $ficha) {
            $cargo = indice_repositorio::categoria_dedicacion($legajo, $ficha['anio']);
            $licencias = indice_repositorio::licencias($legajo, $ficha['anio']);

            $periodos[] = [
                'anio' => $ficha['anio'],
                'k' => $k + 1,
                'ficha_id' => $ficha['ficha_id'],
                'categoria' => $cargo['categoria'],
                'dedicacion' => $cargo['dedicacion'],
                'lic_con_goce' => $licencias['lic_con_goce'],
                'lic_sin_goce' => $licencias['lic_sin_goce'],
            ];
        }

        $correccion = (new \Pruebas\Indice\Motor\CorreccionTemporal())->calcular(array_map(
            static fn (array $p) => ['k' => $p['k'], 'lic_con_goce' => $p['lic_con_goce'], 'lic_sin_goce' => $p['lic_sin_goce']],
            $periodos,
        ));

        foreach ($periodos as $i => &$periodo) {
            $periodo['fc'] = $correccion[$i]['fc'];
            $periodo['fck'] = $correccion[$i]['fck'];
        }
        unset($periodo);

        indice_repositorio::guardarEvaluacionPeriodo($legajo, $periodos);
    }

    /**
     * Cierra el período de un docente y calcula/persiste todas las
     * dimensiones sembradas (indice_repositorio::dimensiones(), hoy AD1..AD8)
     * para cada año con ficha. Es lo que hace falta correr, por docente, para
     * que indice.vista_idd quede poblada sin intervención manual — hasta
     * ahora esto se hizo a mano, dimensión por dimensión, sólo para el
     * legajo de prueba.
     */
    static function calcularDocente(int $legajo): void
    {
        self::cerrarPeriodo($legajo);

        foreach (indice_repositorio::fichas($legajo) as $ficha) {
            foreach (indice_repositorio::dimensiones($ficha['anio']) as $codigo) {
                self::guardarDimension($codigo, $legajo, $ficha['anio']);
            }
        }
    }

    /**
     * calcularDocente() para todos los docentes con al menos una ficha
     * (indice_repositorio::legajos()). Pensado para correrse manualmente
     * como acción administrativa (hoy no hay trigger automático al guardar
     * una ficha ni un cron armado) — 227 legajos / 423 fichas al momento de
     * escribir esto, sin problema de performance para correrlo síncrono.
     */
    static function calcularTodosLosDocentes(): void
    {
        foreach (indice_repositorio::legajos() as $legajo) {
            self::calcularDocente($legajo);
        }
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
