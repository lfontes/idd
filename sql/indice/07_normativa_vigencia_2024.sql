-- =============================================================================
-- Índice de Desempeño Docente - FCA / UNCuyo
-- Fix: la normativa IDD-2025 también rige para 2024
-- Requiere: 02_siembra_docencia.sql
-- =============================================================================
-- 02_siembra_docencia.sql sembró indice.normativa.vigencia como
-- daterange('2025-01-01', NULL), asumiendo que sólo regía desde 2025.
-- Confirmado por el usuario: es la misma normativa para 2024 y 2025 (el
-- código 'IDD-2025' queda como nombre, no como límite de vigencia). Se
-- extiende para cubrir ambos años.

SET search_path TO indice, public;

UPDATE normativa
   SET vigencia = daterange('2024-01-01', NULL)
 WHERE codigo = 'IDD-2025';
