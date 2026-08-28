-- =============================================================================
-- Índice de Desempeño Docente - FCA / UNCuyo
-- Fix: tipo de componente de AD1 (producto -> lookup)
-- Requiere: 02_siembra_docencia.sql
-- =============================================================================
-- 02_siembra_docencia.sql sembró el componente de AD1 como tipo='producto',
-- campo='cantidad', campo_clave='tipo_formacion' — asumiendo que 'cantidad'
-- viene implícita en 1. Pero docs/indice/Mapeo-origen.md, sección AD1,
-- decisión 1, dice explícitamente: "el componente de AD1 es lookup y no
-- producto: el proveedor no emite cantidad". Y en la práctica
-- indice_repositorio::items_formacion_academica() nunca emite el campo
-- 'cantidad' (ver su propio comentario, misma decisión 1).
--
-- EvaluadorComponentes::producto() no asume 1 cuando falta 'cantidad': usa 0
-- (item->valor() devuelve null -> se castea a '0'). Con la siembra original,
-- cada ítem de AD1 puntuaba cantidad(0) x w = 0 siempre — PD1 daba 0 para
-- cualquier docente, confirmado corriendo el cálculo real end-to-end (ver
-- test/datos/IndiceRepositorioConfiguracionTest.php).

SET search_path TO indice, public;

BEGIN;

UPDATE componente
   SET tipo = 'lookup', campo = NULL
 WHERE dimension_id = (
         SELECT dim.id FROM dimension dim
           JOIN actividad a ON a.id = dim.actividad_id
          WHERE dim.codigo = 'AD1' AND a.codigo = 'AD'
       )
   AND tipo = 'producto';

DO $$
DECLARE
    corregidas integer;
BEGIN
    SELECT count(*) INTO corregidas
      FROM componente c
      JOIN dimension dim ON dim.id = c.dimension_id
      JOIN actividad a ON a.id = dim.actividad_id
     WHERE dim.codigo = 'AD1' AND a.codigo = 'AD' AND c.tipo = 'lookup';

    IF corregidas <> 1 THEN
        RAISE EXCEPTION 'Fix de tipo de componente AD1 incompleto: % filas en lookup (se esperaba 1)', corregidas;
    END IF;
END $$;

COMMIT;
