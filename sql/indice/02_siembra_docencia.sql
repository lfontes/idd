-- =============================================================================
-- Índice de Desempeño Docente - FCA / UNCuyo
-- Siembra de configuración: ACTIVIDAD DOCENTE (AD1 .. AD8)
-- Fuente: IADocente.xlsx (hojas AD, AD1..AD8, Puntaje)
-- Requiere: 01_indice_ddl.sql
-- =============================================================================
-- PENDIENTE DE CONFIRMACIÓN NORMATIVA (marcado como [REVISAR] en el texto):
--   1. AD8: la hoja Puntaje fija UBD8 = 19, pero el Cuadro 2.4.8.3 dice
--      "0-19 -> 0, 20 -> 1, >20 -> 3", lo que implica un mínimo de 20.
--      Se siembra 19 para que la suma de mínimos dé los UBD del Excel
--      (31 para T-EX). El caso de prueba no distingue entre 19 y 20.
--   2. AD3, AD6, AD7: los cuadros describen tramos ("1-5", "1-8", "1-15")
--      pero las fórmulas usan ">1" y dejan el valor exacto 1 en cero.
--      Se siembra según el cuadro, que es el texto normativo.
-- =============================================================================

SET search_path TO indice, public;

BEGIN;

-- -----------------------------------------------------------------------------
-- Tablas de referencia
-- -----------------------------------------------------------------------------
INSERT INTO categoria_docente (codigo, nombre, orden) VALUES
    ('T',   'Titular',                   1),
    ('AS',  'Asociado',                  2),
    ('AJ',  'Adjunto',                   3),
    ('JTP', 'Jefe de Trabajos Prácticos', 4),
    ('AY1', 'Ayudante de Primera',       5),
    ('AY2', 'Ayudante de Segunda',       6);

INSERT INTO dedicacion (codigo, nombre, orden) VALUES
    ('EX', 'Exclusiva',    1),
    ('SE', 'Semiexclusiva', 2),
    ('DS', 'Simple',       3);

-- Combinaciones vigentes según la tabla de umbrales del Excel.
INSERT INTO cargo (categoria, dedicacion)
SELECT c.codigo, d.codigo
  FROM categoria_docente c
  CROSS JOIN dedicacion d
 WHERE NOT (c.codigo = 'AY2' AND d.codigo <> 'DS');

-- -----------------------------------------------------------------------------
-- Normativa y estructura
-- -----------------------------------------------------------------------------
INSERT INTO normativa (codigo, descripcion, vigencia, estado) VALUES
    ('IDD-2025', 'Índice de Desempeño Docente - régimen vigente',
     daterange('2025-01-01', NULL), 'vigente');

INSERT INTO actividad (normativa_id, codigo, nombre, orden)
SELECT id, 'AD', 'Actividad Docente', 1 FROM normativa WHERE codigo = 'IDD-2025';

INSERT INTO dimension (actividad_id, codigo, nombre, orden, proveedor)
SELECT a.id, v.codigo, v.nombre, v.orden, v.proveedor
  FROM actividad a,
       (VALUES
         ('AD1', 'Formación académica',                                  1, 'formacion_academica'),
         ('AD2', 'Actualización y capacitación en docencia',             2, 'capacitacion_docente'),
         ('AD3', 'Docencia en carreras de pregrado y grado',             3, 'espacios_curriculares'),
         ('AD4', 'Docencia de posgrado y otros',                         4, 'docencia_posgrado'),
         ('AD5', 'Participación en reuniones científicas de docencia',   5, 'reuniones_docencia'),
         ('AD6', 'Proyectos en docencia acreditados',                    6, 'proyectos_docencia'),
         ('AD7', 'Formación de recursos humanos',                        7, 'formacion_rrhh'),
         ('AD8', 'Producción de materiales pedagógicos',                 8, 'materiales_pedagogicos')
       ) AS v(codigo, nombre, orden, proveedor)
 WHERE a.codigo = 'AD';

-- -----------------------------------------------------------------------------
-- Parámetros de la normativa
-- -----------------------------------------------------------------------------
INSERT INTO parametro (normativa_id, clave, valor, descripcion)
SELECT n.id, v.clave, v.valor, v.descripcion
  FROM normativa n,
       (VALUES
         ('Che', 90, 'Carga horaria de referencia del espacio curricular (Mapa Docendi)'),
         ('Cee', 90, 'Cantidad de estudiantes de referencia (Mapa Docendi)')
       ) AS v(clave, valor, descripcion)
 WHERE n.codigo = 'IDD-2025';

-- -----------------------------------------------------------------------------
-- Cuadros de valoración
-- -----------------------------------------------------------------------------
INSERT INTO tabla_valoracion (normativa_id, codigo, descripcion)
SELECT n.id, v.codigo, v.descripcion
  FROM normativa n,
       (VALUES
         ('ad1_formacion_academica', 'Cuadro 2.4.1.1: Puntaje (w) según formación académica'),
         ('ad3_complejidad_ec',      'Cuadro 2.4.3.1: Ponderación de los espacios curriculares según complejidad'),
         ('ad4_carrera_posgrado',    'Cuadro 2.4.4.1: Valoración según carrera'),
         ('ad5_participacion',       'Cuadro 2.4.5.1: Valoración según el tipo de participación'),
         ('ad5_presentacion',        'Cuadro 2.4.5.2: Valoración según tipo de presentación'),
         ('ad6_participacion_pry',   'Cuadro 2.4.6.1: Ponderación según participación en el proyecto'),
         ('ad7_categoria_rrhh',      'Cuadro 2.4.7.1: Valoración según categoría del recurso formado'),
         ('ad8_material',            'Cuadro 2.4.8.1: Valoración según material pedagógico'),
         ('ad8_tarea',               'Cuadro 2.4.8.2: Valoración según tarea')
       ) AS v(codigo, descripcion)
 WHERE n.codigo = 'IDD-2025';

-- AD1 - puntaje w según formación académica
INSERT INTO valoracion (tabla_id, clave, valor)
SELECT t.id, v.clave, v.valor
  FROM tabla_valoracion t,
       (VALUES ('Pregrado', 1), ('Grado', 3), ('Posdoctorado', 6), ('Doctorado', 5),
               ('Maestría', 3), ('Especialización', 1), ('Diplomado', 0.5)
       ) AS v(clave, valor)
 WHERE t.codigo = 'ad1_formacion_academica';

-- AD3 - complejidad del espacio curricular (Dac)
INSERT INTO valoracion (tabla_id, clave, valor)
SELECT t.id, v.clave, v.valor
  FROM tabla_valoracion t,
       (VALUES ('1', 2),   -- Asignaturas teóricas
               ('2', 3),   -- Asignaturas teóricas-aplicadas
               ('3', 5),   -- Talleres y laboratorios
               ('4', 12)   -- Prácticas de campo supervisadas
       ) AS v(clave, valor)
 WHERE t.codigo = 'ad3_complejidad_ec';

-- AD4 - valoración según carrera de posgrado
INSERT INTO valoracion (tabla_id, clave, valor)
SELECT t.id, v.clave, v.valor
  FROM tabla_valoracion t,
       (VALUES ('Doctorado', 7), ('Maestría', 5), ('Especialización', 3),
               ('Diplomatura', 1), ('Diplomado', 0.5)
       ) AS v(clave, valor)
 WHERE t.codigo = 'ad4_carrera_posgrado';

-- AD5 - tipo de participación en la reunión
INSERT INTO valoracion (tabla_id, clave, valor)
SELECT t.id, v.clave, v.valor
  FROM tabla_valoracion t,
       (VALUES ('Asistente', 1), ('Expositor', 3), ('Organización', 4), ('Evaluador', 5)
       ) AS v(clave, valor)
 WHERE t.codigo = 'ad5_participacion';

-- AD5 - tipo de presentación cruzado con referato (0 = sin referato, 1 = con referato)
INSERT INTO valoracion (tabla_id, clave, clave_2, valor)
SELECT t.id, v.clave, v.clave_2, v.valor
  FROM tabla_valoracion t,
       (VALUES ('Resumen', '0', 1), ('Resumen', '1', 3),
               ('Trabajo Extendido', '0', 3), ('Trabajo Extendido', '1', 5),
               ('Trabajo Completo', '0', 5),  ('Trabajo Completo', '1', 8)
       ) AS v(clave, clave_2, valor)
 WHERE t.codigo = 'ad5_presentacion';

-- AD6 - participación en proyectos de docencia
INSERT INTO valoracion (tabla_id, clave, valor)
SELECT t.id, v.clave, v.valor
  FROM tabla_valoracion t,
       (VALUES ('Director', 8), ('Codirector', 5), ('Participante', 1), ('Evaluador', 5)
       ) AS v(clave, valor)
 WHERE t.codigo = 'ad6_participacion_pry';

-- AD7 - categoría del recurso humano formado
INSERT INTO valoracion (tabla_id, clave, valor)
SELECT t.id, v.clave, v.valor
  FROM tabla_valoracion t,
       (VALUES ('Docente', 10), ('Adscripto', 5), ('Pasante', 3), ('Concurrencia', 3)
       ) AS v(clave, valor)
 WHERE t.codigo = 'ad7_categoria_rrhh';

-- AD8 - material pedagógico y tarea
INSERT INTO valoracion (tabla_id, clave, valor)
SELECT t.id, v.clave, v.valor
  FROM tabla_valoracion t,
       (VALUES ('Apuntes de clase', 8), ('Trabajo Práctico', 5), ('Entorno Virtual', 8)
       ) AS v(clave, valor)
 WHERE t.codigo = 'ad8_material';

INSERT INTO valoracion (tabla_id, clave, valor)
SELECT t.id, v.clave, v.valor
  FROM tabla_valoracion t,
       (VALUES ('Desarrollo', 5), ('Actualización', 1)
       ) AS v(clave, valor)
 WHERE t.codigo = 'ad8_tarea';

-- -----------------------------------------------------------------------------
-- Componentes de puntaje
-- -----------------------------------------------------------------------------
-- Nomenclatura: d() resuelve la dimensión por código, tv() el cuadro por código.

CREATE OR REPLACE FUNCTION pg_temp.d(p_codigo varchar) RETURNS integer AS $$
    SELECT dim.id FROM indice.dimension dim
      JOIN indice.actividad a ON a.id = dim.actividad_id
     WHERE dim.codigo = p_codigo AND a.codigo = 'AD';
$$ LANGUAGE sql STABLE;

CREATE OR REPLACE FUNCTION pg_temp.tv(p_codigo varchar) RETURNS integer AS $$
    SELECT id FROM indice.tabla_valoracion WHERE codigo = p_codigo;
$$ LANGUAGE sql STABLE;

-- AD1: (fa.c.wfa) = cantidad x w del cuadro. Un solo término, dos factores
-- implícitos en 'producto'. El proveedor emite una fila por formación declarada.
INSERT INTO componente (dimension_id, termino, orden, tipo, campo, campo_clave, tabla_valoracion_id)
VALUES (pg_temp.d('AD1'), 1, 1, 'producto', 'cantidad', 'tipo_formacion',
        pg_temp.tv('ad1_formacion_academica'));

-- AD2: horas / divisor según combinación, más los adicionales de pasantía y
-- distinción. Tres términos que se suman.
INSERT INTO componente (dimension_id, termino, orden, tipo, campo, params)
VALUES (pg_temp.d('AD2'), 1, 1, 'divisor_condicional', 'horas', '{
    "reglas": [
      {"cuando": {"curso": 1, "aprobado": 1},          "divisor": 25},
      {"cuando": {"curso": 1, "asistencia": 1},        "divisor": 50},
      {"cuando": {"taller": 1, "aprobado": 1},         "divisor": 25},
      {"cuando": {"taller": 1, "asistencia": 1},       "divisor": 50},
      {"cuando": {"form_obligatoria": 1, "aprobado": 1}, "divisor": 25}
    ],
    "defecto": 0
  }'::jsonb);

INSERT INTO componente (dimension_id, termino, orden, tipo, campo_clave, constante)
VALUES (pg_temp.d('AD2'), 2, 1, 'condicional', 'pasantia',   5),
       (pg_temp.d('AD2'), 3, 1, 'condicional', 'distincion', 0.5);

-- AD3: (Ch / Che) x (Cei / Cee) x complejidad. Un término, tres factores.
INSERT INTO componente (dimension_id, termino, orden, tipo, campo, campo_clave,
                        tabla_valoracion_id, divisor_parametro)
VALUES (pg_temp.d('AD3'), 1, 1, 'proporcion', 'carga_horaria',        NULL, NULL, 'Che'),
       (pg_temp.d('AD3'), 1, 2, 'proporcion', 'estudiantes_inscriptos', NULL, NULL, 'Cee'),
       (pg_temp.d('AD3'), 1, 3, 'lookup',     NULL, 'tipo_espacio',
        pg_temp.tv('ad3_complejidad_ec'), NULL);

-- AD4: créditos x (participación / 100) x valoración de la carrera.
INSERT INTO componente (dimension_id, termino, orden, tipo, campo, campo_clave,
                        tabla_valoracion_id, divisor)
VALUES (pg_temp.d('AD4'), 1, 1, 'directo',    'creditos',      NULL, NULL, NULL),
       (pg_temp.d('AD4'), 1, 2, 'proporcion', 'participacion', NULL, NULL, 100),
       (pg_temp.d('AD4'), 1, 3, 'lookup',     NULL, 'carrera',
        pg_temp.tv('ad4_carrera_posgrado'), NULL);

-- AD5: participación + presentación (esta última, cuadro de doble entrada).
INSERT INTO componente (dimension_id, termino, orden, tipo, campo_clave, campo_clave_2,
                        tabla_valoracion_id)
VALUES (pg_temp.d('AD5'), 1, 1, 'lookup', 'tipo_participacion', NULL,
        pg_temp.tv('ad5_participacion')),
       (pg_temp.d('AD5'), 2, 1, 'lookup', 'tipo_presentacion',  'referato',
        pg_temp.tv('ad5_presentacion'));

-- AD6 y AD7: un único lookup.
INSERT INTO componente (dimension_id, termino, orden, tipo, campo_clave, tabla_valoracion_id)
VALUES (pg_temp.d('AD6'), 1, 1, 'lookup', 'tipo_participacion',
        pg_temp.tv('ad6_participacion_pry')),
       (pg_temp.d('AD7'), 1, 1, 'lookup', 'categoria_rrhh',
        pg_temp.tv('ad7_categoria_rrhh'));

-- AD8: valor del material x valor de la tarea. Un término, dos factores.
INSERT INTO componente (dimension_id, termino, orden, tipo, campo_clave, tabla_valoracion_id)
VALUES (pg_temp.d('AD8'), 1, 1, 'lookup', 'material', pg_temp.tv('ad8_material')),
       (pg_temp.d('AD8'), 1, 2, 'lookup', 'tarea',    pg_temp.tv('ad8_tarea'));

-- -----------------------------------------------------------------------------
-- Umbrales (mínimo = umbral básico de la dimensión; superior = techo del tramo 1)
-- -----------------------------------------------------------------------------
-- AD1 es la única dimensión de docencia cuyo umbral varía por categoría.
INSERT INTO umbral (dimension_id, categoria, dedicacion, minimo, superior)
SELECT pg_temp.d('AD1'), c.categoria, c.dedicacion, v.minimo, v.superior
  FROM cargo c
  JOIN (VALUES ('T', 6, 8), ('AS', 6, 8), ('AJ', 6, 8),
               ('JTP', 3, 5), ('AY1', 1, 3), ('AY2', 0, 2)
       ) AS v(categoria, minimo, superior) ON v.categoria = c.categoria;

-- AD2 a AD7: mismo umbral para toda categoría y dedicación.
INSERT INTO umbral (dimension_id, categoria, dedicacion, minimo, superior)
SELECT pg_temp.d(v.dim), c.categoria, c.dedicacion, v.minimo, v.superior
  FROM cargo c
  CROSS JOIN (VALUES ('AD2', 1,  5),
                     ('AD3', 1,  5),
                     ('AD4', 1,  2),
                     ('AD5', 1,  5),
                     ('AD6', 1,  8),
                     ('AD7', 1, 15)
             ) AS v(dim, minimo, superior);

-- AD8: [REVISAR] mínimo 19 (UBD8 de la hoja Puntaje) contra 20 (Cuadro 2.4.8.3).
INSERT INTO umbral (dimension_id, categoria, dedicacion, minimo, superior)
SELECT pg_temp.d('AD8'), c.categoria, c.dedicacion, 19, 19
  FROM cargo c;

COMMIT;

-- =============================================================================
-- Verificación: la suma de mínimos debe reproducir los UBD de la hoja Puntaje
--   T, AS, AJ -> 31    JTP -> 28    AY1 -> 26    AY2 -> 25
-- =============================================================================
SELECT categoria, dedicacion, umbral_calculado
  FROM v_control_umbrales
 WHERE codigo = 'AD'
 ORDER BY dedicacion, categoria;
