-- =============================================================================
-- Índice de Desempeño Docente - FCA / UNCuyo
-- Fix: las tablas de valoración de AD5 estaban keyed por texto, no por id
-- Requiere: 02_siembra_docencia.sql
-- =============================================================================
-- Mismo bug que 09_ad4_fix_tipo_posgrado.sql, esta vez en las dos tablas de
-- AD5 (indice.tabla_valoracion 4 y 5): sembradas con claves de TEXTO
-- ('Asistente', 'Organización'...) copiadas del Excel en vez del id numérico
-- de public.c35_tipos_participacion/c35_tipos_presentacion. Viola la regla
-- de resolver por id (no por texto) y es frágil contra el problema de
-- encoding LATIN1/UTF8 de 'desempenio' -- 'Organización' en la tabla de
-- valoración vs 'Organizacion' (sin tilde) en el catálogo real ya
-- divergían.
--
-- Los componentes de AD5 (indice.componente) ya usan campo_clave
-- 'tipo_participacion'/'tipo_presentacion' -- no hacía falta tocarlos, sólo
-- las claves de indice.valoracion. clave_2 de la tabla 5 (referato, '0'/'1')
-- ya estaba bien: no es texto.

SET search_path TO indice, public;

BEGIN;

DELETE FROM valoracion WHERE tabla_id IN (4, 5);

-- tabla 4: tipo_participacion (public.c35_tipos_participacion)
INSERT INTO valoracion (tabla_id, clave, valor) VALUES
    (4, '1', 1.000000), -- Asistente
    (4, '2', 3.000000), -- Expositor
    (4, '3', 4.000000), -- Organización
    (4, '4', 5.000000); -- Evaluador

-- tabla 5: tipo_presentacion x referato (public.c35_tipos_presentacion,
-- columnas valor/valor_referato)
INSERT INTO valoracion (tabla_id, clave, clave_2, valor) VALUES
    (5, '1', '0', 1.000000), -- Resumen, sin referato
    (5, '1', '1', 3.000000), -- Resumen, con referato
    (5, '2', '0', 3.000000), -- Trabajo extendido, sin referato
    (5, '2', '1', 5.000000), -- Trabajo extendido, con referato
    (5, '3', '0', 5.000000), -- Trabajo Completo, sin referato
    (5, '3', '1', 8.000000); -- Trabajo Completo, con referato

DO $$
DECLARE
    filas_4 integer;
    filas_5 integer;
BEGIN
    SELECT count(*) INTO filas_4 FROM valoracion WHERE tabla_id = 4;
    IF filas_4 <> 4 THEN
        RAISE EXCEPTION 'Fix de valoracion AD5 (tabla 4) incompleto: % filas (se esperaban 4)', filas_4;
    END IF;

    SELECT count(*) INTO filas_5 FROM valoracion WHERE tabla_id = 5;
    IF filas_5 <> 6 THEN
        RAISE EXCEPTION 'Fix de valoracion AD5 (tabla 5) incompleto: % filas (se esperaban 6)', filas_5;
    END IF;
END $$;

COMMIT;
