-- =============================================================================
-- Índice de Desempeño Docente - FCA / UNCuyo
-- Fix: el lookup de AD4 estaba etiquetado 'carrera' pero es tipo_posgrado
-- Requiere: 02_siembra_docencia.sql
-- =============================================================================
-- 02_siembra_docencia.sql sembró el tercer componente de AD4 como
-- campo_clave='carrera', apuntando a indice.tabla_valoracion id 3
-- ('ad4_carrera_posgrado', Cuadro 2.4.4.1). Pero esa tabla estaba cargada con
-- claves de TEXTO ('Doctorado', 'Maestría', 'Especialización'...) que calzan
-- exactamente con public.c34_posgrado_tipos.tipo_posgrado, no con ninguna
-- "carrera": es el tipo de posgrado (Doctorado/Maestría/...), mal etiquetado
-- al sembrar. Confirmado por el usuario.
--
-- Dos problemas con la siembra original:
--   1. Clave de texto con tildes en vez de id numérico -- viola la regla del
--      proyecto (resolver por id, nunca por texto) y es frágil contra el
--      problema de encoding LATIN1/UTF8 de la fuente 'desempenio'.
--   2. Faltaba 'Otros' (public.c34_posgrado_tipos.id=6): 85 de 386 filas
--      reales de c34_docec_posgrado (22%) usan ese tipo. Con la siembra
--      original, indice_repositorio::items_docencia_posgrado() habría hecho
--      tirar RuntimeException en EvaluadorComponentes::lookup() para esas.
--
-- Se corrige: el componente pasa a resolver por indice.dimension.proveedor
-- ('docencia_posgrado') emitiendo 'tipo_posgrado' = public.c34_docec_posgrado.tipo_posgrado_id
-- como string, y la tabla de valoración queda keyed por ese mismo id
-- (1..6), replicando public.c34_posgrado_tipos.valor.

SET search_path TO indice, public;

BEGIN;

UPDATE componente c
   SET campo_clave = 'tipo_posgrado'
  FROM dimension dim
  JOIN actividad a ON a.id = dim.actividad_id
 WHERE c.dimension_id = dim.id
   AND dim.codigo = 'AD4' AND a.codigo = 'AD'
   AND c.tipo = 'lookup'
   AND c.campo_clave = 'carrera';

DELETE FROM valoracion WHERE tabla_id = 3;

INSERT INTO valoracion (tabla_id, clave, valor) VALUES
    (3, '1', 7.000000), -- Doctorado
    (3, '2', 5.000000), -- Maestría
    (3, '3', 3.000000), -- Especialización
    (3, '4', 1.000000), -- Diplomatura
    (3, '5', 0.500000), -- Diplomado
    (3, '6', 1.000000); -- Otros

DO $$
DECLARE
    corregido integer;
    filas_valoracion integer;
BEGIN
    SELECT count(*) INTO corregido
      FROM componente c
      JOIN dimension dim ON dim.id = c.dimension_id
      JOIN actividad a ON a.id = dim.actividad_id
     WHERE dim.codigo = 'AD4' AND a.codigo = 'AD' AND c.tipo = 'lookup' AND c.campo_clave = 'tipo_posgrado';

    IF corregido <> 1 THEN
        RAISE EXCEPTION 'Fix de campo_clave AD4 incompleto: % filas con tipo_posgrado (se esperaba 1)', corregido;
    END IF;

    SELECT count(*) INTO filas_valoracion FROM valoracion WHERE tabla_id = 3;
    IF filas_valoracion <> 6 THEN
        RAISE EXCEPTION 'Fix de valoracion AD4 incompleto: % filas en tabla_id=3 (se esperaban 6)', filas_valoracion;
    END IF;
END $$;

COMMIT;
