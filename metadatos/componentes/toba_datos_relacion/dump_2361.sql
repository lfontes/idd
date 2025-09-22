------------------------------------------------------------
--[2361]--  Ficha - DR 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_datos_relacion', --clase
	'13', --punto_montaje
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'Ficha - DR', --nombre
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
	'2025-03-19 23:02:17', --creacion
	NULL  --posicion_botonera
);
--- FIN Grupo de desarrollo 0

------------------------------------------------------------
-- apex_objeto_datos_rel
------------------------------------------------------------
INSERT INTO apex_objeto_datos_rel (proyecto, objeto, debug, clave, ap, punto_montaje, ap_clase, ap_archivo, sinc_susp_constraints, sinc_orden_automatico, sinc_lock_optimista) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'0', --debug
	NULL, --clave
	'2', --ap
	'13', --punto_montaje
	NULL, --ap_clase
	NULL, --ap_archivo
	'0', --sinc_susp_constraints
	'1', --sinc_orden_automatico
	'1'  --sinc_lock_optimista
);

------------------------------------------------------------
-- apex_objeto_dependencias
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1253', --dep_id
	'2361', --objeto_consumidor
	'2376', --objeto_proveedor
	'actualizacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'3'  --orden
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000015', --dep_id
	'2361', --objeto_consumidor
	'1832000014', --objeto_proveedor
	'cap_libros', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'20'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000053', --dep_id
	'2361', --objeto_consumidor
	'1832000053', --objeto_proveedor
	'cap_libros_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'38'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000084', --dep_id
	'2361', --objeto_consumidor
	'1832000088', --objeto_proveedor
	'cap_libros_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'52'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000030', --dep_id
	'2361', --objeto_consumidor
	'1832000029', --objeto_proveedor
	'cap_libros_vinculacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'27'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000002', --dep_id
	'2361', --objeto_consumidor
	'1832000001', --objeto_proveedor
	'cargos', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'13'  --orden
);
--- FIN Grupo de desarrollo 1832

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1339', --dep_id
	'2361', --objeto_consumidor
	'2380', --objeto_proveedor
	'carreras', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'10'  --orden
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000004', --dep_id
	'2361', --objeto_consumidor
	'1832000003', --objeto_proveedor
	'categorizacion_inv', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'14'  --orden
);
--- FIN Grupo de desarrollo 1832

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1271', --dep_id
	'2361', --objeto_consumidor
	'2398', --objeto_proveedor
	'docec_facultad', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'4'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1273', --dep_id
	'2361', --objeto_consumidor
	'2400', --objeto_proveedor
	'docec_posgrado', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'5'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1340', --dep_id
	'2361', --objeto_consumidor
	'2386', --objeto_proveedor
	'espacios_curriculares', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'11'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1233', --dep_id
	'2361', --objeto_consumidor
	'2335', --objeto_proveedor
	'ficha', --identificador
	'1', --parametros_a
	'1', --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'1'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1246', --dep_id
	'2361', --objeto_consumidor
	'2368', --objeto_proveedor
	'formacion_academica', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'2'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1280', --dep_id
	'2361', --objeto_consumidor
	'2413', --objeto_proveedor
	'formacion_docec', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'8'  --orden
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000063', --dep_id
	'2361', --objeto_consumidor
	'1832000063', --objeto_proveedor
	'formacion_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'43'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000040', --dep_id
	'2361', --objeto_consumidor
	'1832000039', --objeto_proveedor
	'formacion_vinc', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'32'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000075', --dep_id
	'2361', --objeto_consumidor
	'1832000079', --objeto_proveedor
	'gestion_catedra', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'48'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000073', --dep_id
	'2361', --objeto_consumidor
	'1832000077', --objeto_proveedor
	'gobierno_depar', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'47'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000071', --dep_id
	'2361', --objeto_consumidor
	'1832000075', --objeto_proveedor
	'gobierno_inst', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'46'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000069', --dep_id
	'2361', --objeto_consumidor
	'1832000073', --objeto_proveedor
	'gobierno_univ', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'45'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000007', --dep_id
	'2361', --objeto_consumidor
	'1832000007', --objeto_proveedor
	'impacto_pub', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'16'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000014', --dep_id
	'2361', --objeto_consumidor
	'1832000013', --objeto_proveedor
	'libros', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'19'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000027', --dep_id
	'2361', --objeto_consumidor
	'1832000026', --objeto_proveedor
	'libros_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'26'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000052', --dep_id
	'2361', --objeto_consumidor
	'1832000052', --objeto_proveedor
	'libros_extension_632', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'37'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000082', --dep_id
	'2361', --objeto_consumidor
	'1832000086', --objeto_proveedor
	'libros_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'51'  --orden
);
--- FIN Grupo de desarrollo 1832

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1344', --dep_id
	'2361', --objeto_consumidor
	'2477', --objeto_proveedor
	'licencias', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'12'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1281', --dep_id
	'2361', --objeto_consumidor
	'2415', --objeto_proveedor
	'materiales_pedag', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'9'  --orden
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000022', --dep_id
	'2361', --objeto_consumidor
	'1832000021', --objeto_proveedor
	'part_comite', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'23'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000088', --dep_id
	'2361', --objeto_consumidor
	'1832000092', --objeto_proveedor
	'part_divulg_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'54'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000059', --dep_id
	'2361', --objeto_consumidor
	'1832000059', --objeto_proveedor
	'part_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'41'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000086', --dep_id
	'2361', --objeto_consumidor
	'1832000090', --objeto_proveedor
	'part_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'53'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000020', --dep_id
	'2361', --objeto_consumidor
	'1832000019', --objeto_proveedor
	'part_reun_cientificas', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'22'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000044', --dep_id
	'2361', --objeto_consumidor
	'1832000044', --objeto_proveedor
	'participacion_vinc', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'34'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000018', --dep_id
	'2361', --objeto_consumidor
	'1832000017', --objeto_proveedor
	'patentes', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'21'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000055', --dep_id
	'2361', --objeto_consumidor
	'1832000055', --objeto_proveedor
	'patentes_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'39'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000032', --dep_id
	'2361', --objeto_consumidor
	'1832000031', --objeto_proveedor
	'patentes_vinculacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'28'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000061', --dep_id
	'2361', --objeto_consumidor
	'1832000061', --objeto_proveedor
	'premios_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'42'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000090', --dep_id
	'2361', --objeto_consumidor
	'1832000094', --objeto_proveedor
	'premios_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'55'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000036', --dep_id
	'2361', --objeto_consumidor
	'1832000035', --objeto_proveedor
	'premios_vinc_internac', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'30'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000042', --dep_id
	'2361', --objeto_consumidor
	'1832000041', --objeto_proveedor
	'promocion_vinc', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'33'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000005', --dep_id
	'2361', --objeto_consumidor
	'1832000005', --objeto_proveedor
	'proy_acreditados', --identificador
	'', --parametros_a
	'', --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'15'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000024', --dep_id
	'2361', --objeto_consumidor
	'1832000023', --objeto_proveedor
	'proy_acreditados_vinc', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'24'  --orden
);
--- FIN Grupo de desarrollo 1832

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1277', --dep_id
	'2361', --objeto_consumidor
	'2408', --objeto_proveedor
	'proy_educativos', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'7'  --orden
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000046', --dep_id
	'2361', --objeto_consumidor
	'1832000046', --objeto_proveedor
	'proy_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'35'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000077', --dep_id
	'2361', --objeto_consumidor
	'1832000081', --objeto_proveedor
	'proy_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'49'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000009', --dep_id
	'2361', --objeto_consumidor
	'1832000009', --objeto_proveedor
	'publ_rev_cientificas', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'17'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000012', --dep_id
	'2361', --objeto_consumidor
	'1832000011', --objeto_proveedor
	'publ_rev_divulgacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'18'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000048', --dep_id
	'2361', --objeto_consumidor
	'1832000048', --objeto_proveedor
	'publ_rev_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'36'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000080', --dep_id
	'2361', --objeto_consumidor
	'1832000084', --objeto_proveedor
	'publ_rev_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'50'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000026', --dep_id
	'2361', --objeto_consumidor
	'1832000025', --objeto_proveedor
	'publ_rev_vinculacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'25'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000038', --dep_id
	'2361', --objeto_consumidor
	'1832000037', --objeto_proveedor
	'reconocimientos', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'31'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000056', --dep_id
	'2361', --objeto_consumidor
	'1832000056', --objeto_proveedor
	'registros_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'40'  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000033', --dep_id
	'2361', --objeto_consumidor
	'1832000032', --objeto_proveedor
	'registros_vinculacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'29'  --orden
);
--- FIN Grupo de desarrollo 1832

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1275', --dep_id
	'2361', --objeto_consumidor
	'2404', --objeto_proveedor
	'reu_cientificas', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'6'  --orden
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000064', --dep_id
	'2361', --objeto_consumidor
	'1832000064', --objeto_proveedor
	'servicios_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	'44'  --orden
);
--- FIN Grupo de desarrollo 1832

------------------------------------------------------------
-- apex_objeto_datos_rel_asoc
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'45', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'2368', --hijo_objeto
	'formacion_academica', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'1'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'48', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'2376', --hijo_objeto
	'actualizacion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'2'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'49', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'2398', --hijo_objeto
	'docec_facultad', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'3'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'50', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'2400', --hijo_objeto
	'docec_posgrado', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'4'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'51', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'2404', --hijo_objeto
	'reu_cientificas', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'5'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'52', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'2408', --hijo_objeto
	'proy_educativos', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'6'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'53', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'2413', --hijo_objeto
	'formacion_docec', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'7'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'54', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'2415', --hijo_objeto
	'materiales_pedag', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'8'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'55', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2380', --padre_objeto
	'carreras', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'2386', --hijo_objeto
	'espacios_curriculares', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'9'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'56', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'2477', --hijo_objeto
	'licencias', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'10'  --orden
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000001', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000001', --hijo_objeto
	'cargos', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'11'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000002', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000003', --hijo_objeto
	'categorizacion_inv', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'12'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000003', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000005', --hijo_objeto
	'proy_acreditados', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'13'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000004', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000007', --hijo_objeto
	'impacto_pub', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'14'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000005', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000009', --hijo_objeto
	'publ_rev_cientificas', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'15'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000006', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000011', --hijo_objeto
	'publ_rev_divulgacion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'16'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000007', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000013', --hijo_objeto
	'libros', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'17'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000008', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000014', --hijo_objeto
	'cap_libros', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'18'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000009', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000017', --hijo_objeto
	'patentes', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'19'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000010', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000019', --hijo_objeto
	'part_reun_cientificas', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'20'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000011', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000021', --hijo_objeto
	'part_comite', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'21'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000012', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000023', --hijo_objeto
	'proy_acreditados_vinc', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'22'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000013', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000025', --hijo_objeto
	'publ_rev_vinculacion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'23'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000014', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000026', --hijo_objeto
	'libros_extension', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'24'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000015', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000029', --hijo_objeto
	'cap_libros_vinculacion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'25'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000016', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000031', --hijo_objeto
	'patentes_vinculacion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'26'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000017', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000032', --hijo_objeto
	'registros_vinculacion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'27'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000018', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000035', --hijo_objeto
	'premios_vinc_internac', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'28'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000019', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000037', --hijo_objeto
	'reconocimientos', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'29'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000020', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000039', --hijo_objeto
	'formacion_vinc', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'30'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000021', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000041', --hijo_objeto
	'promocion_vinc', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'31'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000022', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000044', --hijo_objeto
	'participacion_vinc', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'32'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000023', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000046', --hijo_objeto
	'proy_extension', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'33'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000024', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000048', --hijo_objeto
	'publ_rev_extension', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'34'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000026', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000052', --hijo_objeto
	'libros_extension_632', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'35'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000027', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000053', --hijo_objeto
	'cap_libros_extension', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'36'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000028', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000055', --hijo_objeto
	'patentes_extension', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'37'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000029', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000056', --hijo_objeto
	'registros_extension', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'38'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000030', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000059', --hijo_objeto
	'part_extension', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'39'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000031', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000061', --hijo_objeto
	'premios_extension', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'40'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000032', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000063', --hijo_objeto
	'formacion_extension', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'41'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000033', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000064', --hijo_objeto
	'servicios_extension', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'42'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000035', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000073', --hijo_objeto
	'gobierno_univ', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'43'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000036', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000075', --hijo_objeto
	'gobierno_inst', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'44'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000037', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000077', --hijo_objeto
	'gobierno_depar', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'45'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000038', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000079', --hijo_objeto
	'gestion_catedra', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'46'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000039', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000081', --hijo_objeto
	'proy_gestion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'47'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000041', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000084', --hijo_objeto
	'publ_rev_gestion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'48'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000042', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000086', --hijo_objeto
	'libros_gestion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'49'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000043', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000088', --hijo_objeto
	'cap_libros_gestion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'50'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000044', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000090', --hijo_objeto
	'part_gestion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'51'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000045', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000092', --hijo_objeto
	'part_divulg_gestion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'52'  --orden
);
INSERT INTO apex_objeto_datos_rel_asoc (proyecto, objeto, asoc_id, identificador, padre_proyecto, padre_objeto, padre_id, padre_clave, hijo_proyecto, hijo_objeto, hijo_id, hijo_clave, cascada, orden) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000046', --asoc_id
	NULL, --identificador
	'pruebas', --padre_proyecto
	'2335', --padre_objeto
	'ficha', --padre_id
	NULL, --padre_clave
	'pruebas', --hijo_proyecto
	'1832000094', --hijo_objeto
	'premios_gestion', --hijo_id
	NULL, --hijo_clave
	NULL, --cascada
	'53'  --orden
);
--- FIN Grupo de desarrollo 1832

------------------------------------------------------------
-- apex_objeto_rel_columnas_asoc
------------------------------------------------------------
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'45', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'2368', --hijo_objeto
	'898'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'48', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'2376', --hijo_objeto
	'927'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'49', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'2398', --hijo_objeto
	'949'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'50', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'2400', --hijo_objeto
	'960'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'51', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'2404', --hijo_objeto
	'967'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'52', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'2408', --hijo_objeto
	'984'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'53', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'2413', --hijo_objeto
	'995'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'54', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'2415', --hijo_objeto
	'1003'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'55', --asoc_id
	'2380', --padre_objeto
	'938', --padre_clave
	'2386', --hijo_objeto
	'944'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'56', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'2477', --hijo_objeto
	'1017'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000001', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000001', --hijo_objeto
	'1832000007'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000002', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000003', --hijo_objeto
	'1832000009'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000003', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000005', --hijo_objeto
	'1832000024'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000004', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000007', --hijo_objeto
	'1832000034'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000005', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000009', --hijo_objeto
	'1832000039'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000006', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000011', --hijo_objeto
	'1832000047'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000007', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000013', --hijo_objeto
	'1832000054'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000008', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000014', --hijo_objeto
	'1832000061'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000009', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000017', --hijo_objeto
	'1832000070'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000010', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000019', --hijo_objeto
	'1832000075'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000011', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000021', --hijo_objeto
	'1832000083'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000012', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000023', --hijo_objeto
	'1832000090'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000013', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000025', --hijo_objeto
	'1832000099'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000014', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000026', --hijo_objeto
	'1832000112'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000015', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000029', --hijo_objeto
	'1832000114'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000016', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000031', --hijo_objeto
	'1832000123'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000017', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000032', --hijo_objeto
	'1832000128'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000018', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000035', --hijo_objeto
	'1832000133'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000019', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000037', --hijo_objeto
	'1832000143'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000020', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000039', --hijo_objeto
	'1832000148'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000021', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000041', --hijo_objeto
	'1832000153'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000022', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000044', --hijo_objeto
	'1832000164'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000023', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000046', --hijo_objeto
	'1832000170'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000024', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000048', --hijo_objeto
	'1832000175'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000026', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000052', --hijo_objeto
	'1832000199'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000027', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000053', --hijo_objeto
	'1832000207'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000028', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000055', --hijo_objeto
	'1832000216'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000029', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000056', --hijo_objeto
	'1832000221'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000030', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000059', --hijo_objeto
	'1832000226'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000031', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000061', --hijo_objeto
	'1832000235'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000032', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000063', --hijo_objeto
	'1832000240'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000033', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000064', --hijo_objeto
	'1832000244'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000035', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000073', --hijo_objeto
	'1832000262'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000036', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000075', --hijo_objeto
	'1832000267'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000037', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000077', --hijo_objeto
	'1832000272'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000038', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000079', --hijo_objeto
	'1832000277'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000039', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000081', --hijo_objeto
	'1832000281'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000041', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000084', --hijo_objeto
	'1832000294'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000042', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000086', --hijo_objeto
	'1832000301'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000043', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000088', --hijo_objeto
	'1832000315'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000044', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000090', --hijo_objeto
	'1832000317'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000045', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000092', --hijo_objeto
	'1832000326'  --hijo_clave
);
INSERT INTO apex_objeto_rel_columnas_asoc (proyecto, objeto, asoc_id, padre_objeto, padre_clave, hijo_objeto, hijo_clave) VALUES (
	'pruebas', --proyecto
	'2361', --objeto
	'1832000046', --asoc_id
	'2335', --padre_objeto
	'865', --padre_clave
	'1832000094', --hijo_objeto
	'1832000332'  --hijo_clave
);
