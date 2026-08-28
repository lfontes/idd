-- =============================================================================
-- Índice de Desempeño Docente - FCA / UNCuyo
-- Consulta de verificación: AD1 calculado vs. datos de origen
-- =============================================================================
-- No es una migración (no numerada, no se "aplica"): es de sólo lectura, para
-- correr cuando haga falta y comparar el resultado guardado por
-- indice_orquestador::guardarAD1() contra los datos del Informe de Labor de
-- los que sale.
--
-- Una fila por formación académica declarada (fa.id). Si un docente/año no
-- declaró ninguna, igual aparece una fila (LEFT JOIN) con los datos de
-- ficha/categoría/dedicación y el PD1/AD1 ya calculado, todo en NULL de
-- formación.
--
-- Para acotar a un docente o año puntual, descomentar y completar el WHERE
-- del final (f.legajo_doc = ... / f.anio = ...).

SET search_path TO indice, public;

SELECT
    f.legajo_doc                AS legajo,
    f.anio,
    cd.categoria,
    dd.dedicacion,

    -- Origen: lo que el docente declaró (public.formacion_academica/formacion)
    fa.id                       AS formacion_academica_id,
    fa.titulo                   AS formacion_titulo,
    fa.tipo_formacion           AS tipo_formacion_id,
    fo.tipo_formacion           AS tipo_formacion_nombre,
    fo.valor                    AS valor_en_public_formacion,   -- puede diverger de indice.valoracion (ver 04_ad1_control_divergencia.sql)

    -- Config aplicada: el cuadro 2.4.1.1 real usado para puntuar
    v.valor                     AS w_en_indice_valoracion,

    -- Resultado: lo que quedó guardado por indice_orquestador::guardarAD1()
    ei.puntaje                  AS puntaje_item_guardado,       -- debería ser igual a w_en_indice_valoracion
    ed.puntaje                  AS pd1_anio,                    -- suma de todos los ítems del año
    ed.valoracion               AS ad1,                         -- 0/1/3
    ed.minimo                   AS umbral_minimo,
    ed.superior                 AS umbral_superior

FROM public.ficha f
JOIN public.categorias_doc cd ON cd.id = f.categoria_id
JOIN public.dedicaciones   dd ON dd.id = f.dedicacion_id

LEFT JOIN public.formacion_academica fa ON fa.ficha_id = f.id
LEFT JOIN public.formacion           fo ON fo.id = fa.tipo_formacion

-- La evaluación "provisorio" del docente (indice_repositorio::evaluacion_abierta()).
LEFT JOIN indice.evaluacion e ON e.docente_id = f.legajo_doc

-- La dimensión AD1 dentro de la normativa de esa evaluación.
LEFT JOIN indice.actividad  a   ON a.normativa_id = e.normativa_id AND a.codigo = 'AD'
LEFT JOIN indice.dimension  dim ON dim.actividad_id = a.id AND dim.codigo = 'AD1'

LEFT JOIN indice.evaluacion_dimension ed
       ON ed.evaluacion_id = e.id AND ed.dimension_id = dim.id AND ed.anio = f.anio

LEFT JOIN indice.evaluacion_item ei
       ON ei.evaluacion_id = e.id AND ei.dimension_id = dim.id AND ei.anio = f.anio
      AND ei.origen_tabla = 'formacion_academica' AND ei.origen_id = fa.id

LEFT JOIN indice.tabla_valoracion tv
       ON tv.normativa_id = e.normativa_id AND tv.codigo = 'ad1_formacion_academica'
LEFT JOIN indice.valoracion v
       ON v.tabla_id = tv.id AND v.clave = fa.tipo_formacion::text

-- WHERE f.legajo_doc = 12345
-- AND   f.anio = 2025

ORDER BY f.legajo_doc, f.anio, fa.id;
