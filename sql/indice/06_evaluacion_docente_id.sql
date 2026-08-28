-- =============================================================================
-- Índice de Desempeño Docente - FCA / UNCuyo
-- Fix: indice.evaluacion.docente_id -> legajo (bigint), sin FK
-- Requiere: 01_indice_ddl.sql
-- =============================================================================
-- El DDL original dejó docente_id como integer con el comentario "Ajustar al
-- nombre real" (era un placeholder). No hay tabla local de "docente" con id
-- propio: public.docentes no existe en esta base (dt_docentes.php parece
-- apuntar a otra fuente); public.agentes tiene clave compuesta
-- (legajo, ncargo) — una fila por cargo, no por persona, no sirve como
-- identidad simple.
--
-- Se usa legajo_doc de public.ficha, que ya es la identidad estable que usa
-- todo el módulo (items_formacion_academica, categoria_dedicacion). Sin FK:
-- ni siquiera ficha.legajo_doc tiene FK a agentes hoy, mismo criterio.

SET search_path TO indice, public;

BEGIN;

-- v_matriz_dimension (01_indice_ddl.sql) depende de evaluacion.docente_id;
-- hay que recrearla igual, no hay ALTER COLUMN TYPE con vistas dependientes.
DROP VIEW v_matriz_dimension;

ALTER TABLE evaluacion ALTER COLUMN docente_id TYPE bigint;

COMMENT ON COLUMN evaluacion.docente_id IS
    'legajo_doc de public.ficha. Sin FK (tampoco la tiene ficha.legajo_doc): agentes/ficha son snapshots del Informe de Labor, no catálogos estables.';

CREATE VIEW v_matriz_dimension AS
SELECT e.id            AS evaluacion_id,
       e.docente_id,
       a.codigo        AS actividad,
       d.codigo        AS dimension,
       d.orden         AS dimension_orden,
       ed.anio,
       ed.puntaje,
       ed.valoracion
  FROM evaluacion            e
  JOIN evaluacion_dimension ed ON ed.evaluacion_id = e.id
  JOIN dimension             d ON d.id  = ed.dimension_id
  JOIN actividad             a ON a.id  = d.actividad_id;

COMMIT;
