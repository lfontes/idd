------------------------------------------------------------
--[2312]--  3_1_formacion_academica 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'pruebas', --proyecto
	'2312', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_datos_tabla', --clase
	'13', --punto_montaje
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'3_1_formacion_academica', --nombre
	NULL, --titulo
	NULL, --colapsable
	NULL, --descripcion
	'pruebas', --fuente_datos_proyecto
	'desempenio', --fuente_datos
	NULL, --solicitud_registrar
	NULL, --solicitud_obj_obs_tipo
	NULL, --solicitud_obj_observacion
	NULL, --parametro_a
	NULL, --parametro_b
	NULL, --parametro_c
	NULL, --parametro_d
	NULL, --parametro_e
	NULL, --parametro_f
	NULL, --usuario
	'2025-03-13 15:20:02', --creacion
	NULL  --posicion_botonera
);
--- FIN Grupo de desarrollo 0

------------------------------------------------------------
-- apex_objeto_db_registros
------------------------------------------------------------
INSERT INTO apex_objeto_db_registros (objeto_proyecto, objeto, max_registros, min_registros, punto_montaje, ap, ap_clase, ap_archivo, tabla, tabla_ext, alias, modificar_claves, fuente_datos_proyecto, fuente_datos, permite_actualizacion_automatica, esquema, esquema_ext) VALUES (
	'pruebas', --objeto_proyecto
	'2312', --objeto
	NULL, --max_registros
	NULL, --min_registros
	'13', --punto_montaje
	'1', --ap
	NULL, --ap_clase
	NULL, --ap_archivo
	'3_1_formacion_academica', --tabla
	NULL, --tabla_ext
	NULL, --alias
	NULL, --modificar_claves
	'pruebas', --fuente_datos_proyecto
	'desempenio', --fuente_datos
	'0', --permite_actualizacion_automatica
	'public', --esquema
	NULL  --esquema_ext
);

------------------------------------------------------------
-- apex_objeto_db_registros_col
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'pruebas', --objeto_proyecto
	'2312', --objeto
	'834', --col_id
	'id', --columna
	'E', --tipo
	'1', --pk
	'"3_1_formacion_academica_id_seq"', --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	NULL  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'pruebas', --objeto_proyecto
	'2312', --objeto
	'835', --col_id
	'ficha_id', --columna
	'E', --tipo
	'0', --pk
	NULL, --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	NULL  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'pruebas', --objeto_proyecto
	'2312', --objeto
	'836', --col_id
	'formacion', --columna
	'E', --tipo
	'0', --pk
	NULL, --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	NULL  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'pruebas', --objeto_proyecto
	'2312', --objeto
	'837', --col_id
	'titulo', --columna
	'C', --tipo
	'0', --pk
	NULL, --secuencia
	NULL, --largo
	NULL, --no_nulo
	'1', --no_nulo_db
	NULL, --externa
	NULL  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'pruebas', --objeto_proyecto
	'2312', --objeto
	'838', --col_id
	'institucion', --columna
	'C', --tipo
	'0', --pk
	NULL, --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	NULL  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'pruebas', --objeto_proyecto
	'2312', --objeto
	'839', --col_id
	'normativa', --columna
	'C', --tipo
	'0', --pk
	NULL, --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	NULL  --tabla
);
INSERT INTO apex_objeto_db_registros_col (objeto_proyecto, objeto, col_id, columna, tipo, pk, secuencia, largo, no_nulo, no_nulo_db, externa, tabla) VALUES (
	'pruebas', --objeto_proyecto
	'2312', --objeto
	'840', --col_id
	'año', --columna
	'E', --tipo
	'0', --pk
	NULL, --secuencia
	NULL, --largo
	NULL, --no_nulo
	'0', --no_nulo_db
	NULL, --externa
	NULL  --tabla
);
--- FIN Grupo de desarrollo 0
