-- =============================================================================
-- Índice de Desempeño Docente - FCA / UNCuyo
-- Vista de lectura: valoración AD1..AD8 por ficha del Informe de Labor
-- Requiere: 01_indice_ddl.sql
-- =============================================================================
-- php/datos/dt_ficha.php ya hace LEFT OUTER JOIN vista_idd AS idd
-- ON (t_f.id = idd.ficha_id) esperando columnas ficha_id, ad1..ad8 -- este
-- script crea esa vista respetando ese contrato.
--
-- No hay columna ficha_id en evaluacion_dimension: el enlace pasa por
-- evaluacion_periodo, que es la tabla que ata cada año calculado a la ficha
-- que lo alimentó (informe_id, nullable si ese año no tuvo ficha presentada).
--
-- ad1..ad8 = valoracion (0/1/3), no el puntaje PD crudo: es la categorización
-- que usa la normativa (Cuadro 2.4.x.x), la misma que mostraba el Excel.

SET search_path TO indice, public;

CREATE VIEW vista_idd AS
SELECT ep.informe_id AS ficha_id,
       MAX(ed.valoracion) FILTER (WHERE d.codigo = 'AD1') AS ad1,
       MAX(ed.valoracion) FILTER (WHERE d.codigo = 'AD2') AS ad2,
       MAX(ed.valoracion) FILTER (WHERE d.codigo = 'AD3') AS ad3,
       MAX(ed.valoracion) FILTER (WHERE d.codigo = 'AD4') AS ad4,
       MAX(ed.valoracion) FILTER (WHERE d.codigo = 'AD5') AS ad5,
       MAX(ed.valoracion) FILTER (WHERE d.codigo = 'AD6') AS ad6,
       MAX(ed.valoracion) FILTER (WHERE d.codigo = 'AD7') AS ad7,
       MAX(ed.valoracion) FILTER (WHERE d.codigo = 'AD8') AS ad8
  FROM evaluacion_dimension ed
  JOIN dimension             d  ON d.id = ed.dimension_id
  JOIN evaluacion_periodo    ep ON ep.evaluacion_id = ed.evaluacion_id
                                AND ep.anio          = ed.anio
 WHERE d.codigo LIKE 'AD%'
   AND ep.informe_id IS NOT NULL
 GROUP BY ep.informe_id;
