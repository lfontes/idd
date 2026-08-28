-- =============================================================================
-- Índice de Desempeño Docente - FCA / UNCuyo
-- Fix: claves de indice.valoracion para el cuadro ad1_formacion_academica
-- Requiere: 02_siembra_docencia.sql
-- =============================================================================
-- 02_siembra_docencia.sql sembró indice.valoracion.clave con el nombre de la
-- formación ('Pregrado', 'Grado', ...). docs/indice/Mapeo-origen.md, sección
-- AD1, decisión 5, dice que la clave del cuadro debe ser el id numérico de
-- public.formacion, nunca el nombre — porque indice_repositorio::
-- items_formacion_academica() emite formacion_academica.tipo_formacion como
-- id (string), no como nombre. Con la siembra original, cualquier lookup real
-- de AD1 fallaba: "No hay valoración cargada para la clave '2'".
--
-- El valor (w) de cada fila NO cambia acá: se verificó contra
-- docs/indice/fixtures/IADocente.xlsx, hoja AD1, Cuadro 2.4.1.1, y ya
-- coincide (Pregrado 1, Grado 3, Posdoctorado 6, Doctorado 5, Maestría 3,
-- Especialización 1, Diplomado 0.5). Sólo se corrige la clave.
--
-- La correspondencia nombre -> id se arma a mano (no por join de texto)
-- porque el nombre en indice.valoracion no siempre coincide letra a letra
-- con public.formacion.tipo_formacion ('Diplomado' acá vs 'Diplomatura' en
-- public.formacion; 'Maestría' acá vs 'Maestria' sin tilde en public.formacion).
-- Ver 04_ad1_control_divergencia.sql para el control de esa divergencia.

SET search_path TO indice, public;

BEGIN;

UPDATE valoracion v
   SET clave = m.formacion_id::text
  FROM (VALUES
          ('Pregrado',        1),
          ('Grado',           2),
          ('Maestría',        3),
          ('Doctorado',       4),
          ('Posdoctorado',    5),
          ('Especialización', 6),
          ('Diplomado',       7)
       ) AS m(nombre, formacion_id)
 WHERE v.tabla_id = (SELECT id FROM tabla_valoracion WHERE codigo = 'ad1_formacion_academica')
   AND v.clave = m.nombre;

-- Verificación: las 7 filas del cuadro deben haber quedado con clave numérica
-- y sin ninguna sin actualizar (clave que no matcheó el VALUES de arriba).
DO $$
DECLARE
    total       integer;
    actualizadas integer;
BEGIN
    SELECT count(*) INTO total
      FROM valoracion
     WHERE tabla_id = (SELECT id FROM tabla_valoracion WHERE codigo = 'ad1_formacion_academica');

    SELECT count(*) INTO actualizadas
      FROM valoracion
     WHERE tabla_id = (SELECT id FROM tabla_valoracion WHERE codigo = 'ad1_formacion_academica')
       AND clave ~ '^\d+$';

    IF total <> 7 OR actualizadas <> 7 THEN
        RAISE EXCEPTION 'Fix de claves AD1 incompleto: % filas totales, % con clave numérica (se esperaban 7 y 7)',
            total, actualizadas;
    END IF;
END $$;

COMMIT;
