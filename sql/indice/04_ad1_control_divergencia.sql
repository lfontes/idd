-- =============================================================================
-- Índice de Desempeño Docente - FCA / UNCuyo
-- Control de divergencia: indice.valoracion (AD1) vs public.formacion
-- Requiere: 03_ad1_fix_claves_id.sql
-- =============================================================================
-- docs/indice/Mapeo-origen.md, sección AD1, decisión 4: el peso (w) de cada
-- formación lo manda indice.valoracion, nunca public.formacion.valor —
-- public.formacion no está versionado por normativa, así que si alguien edita
-- valor ahí cambiaría retroactivamente índices ya calculados. Esta consulta,
-- de sólo lectura, es el control: si devuelve filas, alguien tocó
-- public.formacion.valor después de la siembra y hay que revisar si
-- corresponde una nueva normativa (nunca sobreescribir indice.valoracion con
-- estos números sin confirmar contra la planilla origen).
--
-- Al día de este script (10/2025) hay divergencia esperada y ya investigada:
-- public.formacion trae valores desactualizados respecto de
-- docs/indice/fixtures/IADocente.xlsx (hoja AD1, Cuadro 2.4.1.1) para
-- Pregrado (1.50 en public.formacion contra 1.00 en la planilla/índice) y
-- nombres desalineados (Diplomatura/Diplomado, Maestria/Maestría). No se
-- toca public.formacion (esquema de solo lectura para este módulo,
-- .claude/rules/indice.md); es un dato a corregir del lado del Informe de
-- Labor si corresponde, fuera del alcance de este módulo.

SET search_path TO indice, public;

SELECT f.id                AS formacion_id,
       f.tipo_formacion     AS nombre_en_formacion,
       f.valor              AS valor_en_formacion,
       v.valor              AS valor_en_indice,
       f.valor <> v.valor   AS diverge
  FROM public.formacion f
  JOIN indice.valoracion v ON v.clave = f.id::text
  JOIN indice.tabla_valoracion t ON t.id = v.tabla_id
 WHERE t.codigo = 'ad1_formacion_academica'
 ORDER BY f.id;
