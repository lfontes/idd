-- =============================================================================
-- Índice de Desempeño Docente - FCA / UNCuyo
-- Fix: tablas de valoración de AD6/AD7/AD8 keyed por texto, y dos incompletas
-- Requiere: 02_siembra_docencia.sql
-- =============================================================================
-- Mismo bug que 09_ad4_fix_tipo_posgrado.sql / 10_ad5_fix_valoracion_por_id.sql,
-- esta vez en indice.tabla_valoracion 6 (AD6), 7 (AD7), 8 y 9 (AD8): claves
-- de TEXTO copiadas del Excel en vez del id numérico de los catálogos reales
-- (public.c36_tipos_participacion, c37_categorias, c38_materiales_tipos,
-- c38_tareas_tipos).
--
-- Además, dos de las cuatro tablas estaban directamente INCOMPLETAS (no sólo
-- mal keyeadas) frente al catálogo real:
--   - AD7 (categoria_rrhh): faltaba 'Tesista de grado' (id 5).
--   - AD8 material: faltaban 'Desarrollo de apps' (id 4) y 'Publicaciones' (id 5).
-- Para esas tres filas nuevas no hay otra fuente que el valor del catálogo
-- público (valor=1.00 en las tres) -- confirmado por el usuario, mismo
-- criterio que 'Otros' en AD4 (09_ad4_fix_tipo_posgrado.sql).
--
-- AD6 y AD8-tarea sí tenían el catálogo completo, sólo con el problema de
-- texto-vs-id.

SET search_path TO indice, public;

BEGIN;

DELETE FROM valoracion WHERE tabla_id IN (6, 7, 8, 9);

-- tabla 6: tipo_participacion en proyectos (public.c36_tipos_participacion)
INSERT INTO valoracion (tabla_id, clave, valor) VALUES
    (6, '1', 8.000000), -- Director
    (6, '2', 5.000000), -- Co-Director
    (6, '3', 1.000000), -- Participante
    (6, '4', 5.000000); -- Evaluador

-- tabla 7: categoria_rrhh (public.c37_categorias) -- agrega 'Tesista de grado'
INSERT INTO valoracion (tabla_id, clave, valor) VALUES
    (7, '1', 10.000000), -- Docente
    (7, '2', 5.000000),  -- Adscripto
    (7, '3', 3.000000),  -- Pasante
    (7, '4', 3.000000),  -- Concurrente
    (7, '5', 1.000000);  -- Tesista de grado (faltaba en la siembra original)

-- tabla 8: material (public.c38_materiales_tipos) -- agrega 'Desarrollo de
-- apps' y 'Publicaciones'
INSERT INTO valoracion (tabla_id, clave, valor) VALUES
    (8, '1', 8.000000), -- Apunte de clases
    (8, '2', 5.000000), -- Trabajo práctico
    (8, '3', 8.000000), -- Entorno virtual
    (8, '4', 1.000000), -- Desarrollo de apps (faltaba en la siembra original)
    (8, '5', 1.000000); -- Publicaciones (faltaba en la siembra original)

-- tabla 9: tarea (public.c38_tareas_tipos)
INSERT INTO valoracion (tabla_id, clave, valor) VALUES
    (9, '1', 5.000000), -- Desarrollo
    (9, '2', 1.000000); -- Actualización

DO $$
DECLARE
    filas_6 integer;
    filas_7 integer;
    filas_8 integer;
    filas_9 integer;
BEGIN
    SELECT count(*) INTO filas_6 FROM valoracion WHERE tabla_id = 6;
    SELECT count(*) INTO filas_7 FROM valoracion WHERE tabla_id = 7;
    SELECT count(*) INTO filas_8 FROM valoracion WHERE tabla_id = 8;
    SELECT count(*) INTO filas_9 FROM valoracion WHERE tabla_id = 9;

    IF filas_6 <> 4 THEN
        RAISE EXCEPTION 'Fix de valoracion AD6 (tabla 6) incompleto: % filas (se esperaban 4)', filas_6;
    END IF;
    IF filas_7 <> 5 THEN
        RAISE EXCEPTION 'Fix de valoracion AD7 (tabla 7) incompleto: % filas (se esperaban 5)', filas_7;
    END IF;
    IF filas_8 <> 5 THEN
        RAISE EXCEPTION 'Fix de valoracion AD8 material (tabla 8) incompleto: % filas (se esperaban 5)', filas_8;
    END IF;
    IF filas_9 <> 2 THEN
        RAISE EXCEPTION 'Fix de valoracion AD8 tarea (tabla 9) incompleto: % filas (se esperaban 2)', filas_9;
    END IF;
END $$;

COMMIT;
