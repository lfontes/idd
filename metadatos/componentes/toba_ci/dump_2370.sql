------------------------------------------------------------
--[2370]--  Ficha - CI - interno 
------------------------------------------------------------

------------------------------------------------------------
-- apex_objeto
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto (proyecto, objeto, anterior, identificador, reflexivo, clase_proyecto, clase, punto_montaje, subclase, subclase_archivo, objeto_categoria_proyecto, objeto_categoria, nombre, titulo, colapsable, descripcion, fuente_datos_proyecto, fuente_datos, solicitud_registrar, solicitud_obj_obs_tipo, solicitud_obj_observacion, parametro_a, parametro_b, parametro_c, parametro_d, parametro_e, parametro_f, usuario, creacion, posicion_botonera) VALUES (
	'pruebas', --proyecto
	'2370', --objeto
	NULL, --anterior
	NULL, --identificador
	NULL, --reflexivo
	'toba', --clase_proyecto
	'toba_ci', --clase
	'13', --punto_montaje
	'ci_interno', --subclase
	'ficha/ci_interno.php', --subclase_archivo
	NULL, --objeto_categoria_proyecto
	NULL, --objeto_categoria
	'Ficha - CI - interno', --nombre
	NULL, --titulo
	'0', --colapsable
	NULL, --descripcion
	NULL, --fuente_datos_proyecto
	NULL, --fuente_datos
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
	'2025-03-26 16:39:46', --creacion
	'arriba'  --posicion_botonera
);
--- FIN Grupo de desarrollo 0

------------------------------------------------------------
-- apex_objeto_eventos
------------------------------------------------------------

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_eventos (proyecto, evento_id, objeto, identificador, etiqueta, maneja_datos, sobre_fila, confirmacion, estilo, imagen_recurso_origen, imagen, en_botonera, ayuda, orden, ci_predep, implicito, defecto, display_datos_cargados, grupo, accion, accion_imphtml_debug, accion_vinculo_carpeta, accion_vinculo_item, accion_vinculo_objeto, accion_vinculo_popup, accion_vinculo_popup_param, accion_vinculo_target, accion_vinculo_celda, accion_vinculo_servicio, es_seleccion_multiple, es_autovinculo) VALUES (
	'pruebas', --proyecto
	'1832000014', --evento_id
	'2370', --objeto
	'imprimir', --identificador
	'&Generar PDF', --etiqueta
	'0', --maneja_datos
	NULL, --sobre_fila
	NULL, --confirmacion
	NULL, --estilo
	'apex', --imagen_recurso_origen
	'exp_pdf_pag.gif', --imagen
	'1', --en_botonera
	NULL, --ayuda
	'1', --orden
	NULL, --ci_predep
	'0', --implicito
	'0', --defecto
	NULL, --display_datos_cargados
	NULL, --grupo
	'F', --accion
	'1', --accion_imphtml_debug
	NULL, --accion_vinculo_carpeta
	NULL, --accion_vinculo_item
	NULL, --accion_vinculo_objeto
	'0', --accion_vinculo_popup
	NULL, --accion_vinculo_popup_param
	NULL, --accion_vinculo_target
	NULL, --accion_vinculo_celda
	NULL, --accion_vinculo_servicio
	'0', --es_seleccion_multiple
	'0'  --es_autovinculo
);
--- FIN Grupo de desarrollo 1832

------------------------------------------------------------
-- apex_objeto_mt_me
------------------------------------------------------------
INSERT INTO apex_objeto_mt_me (objeto_mt_me_proyecto, objeto_mt_me, ev_procesar_etiq, ev_cancelar_etiq, ancho, alto, posicion_botonera, tipo_navegacion, botonera_barra_item, con_toc, incremental, debug_eventos, activacion_procesar, activacion_cancelar, ev_procesar, ev_cancelar, objetos, post_procesar, metodo_despachador, metodo_opciones) VALUES (
	'pruebas', --objeto_mt_me_proyecto
	'2370', --objeto_mt_me
	NULL, --ev_procesar_etiq
	NULL, --ev_cancelar_etiq
	NULL, --ancho
	NULL, --alto
	NULL, --posicion_botonera
	'tab_h', --tipo_navegacion
	'0', --botonera_barra_item
	'0', --con_toc
	NULL, --incremental
	NULL, --debug_eventos
	NULL, --activacion_procesar
	NULL, --activacion_cancelar
	NULL, --ev_procesar
	NULL, --ev_cancelar
	NULL, --objetos
	NULL, --post_procesar
	NULL, --metodo_despachador
	NULL  --metodo_opciones
);

------------------------------------------------------------
-- apex_objeto_dependencias
------------------------------------------------------------

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000016', --dep_id
	'2370', --objeto_consumidor
	'1832000015', --objeto_proveedor
	'Libros', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 1832

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1252', --dep_id
	'2370', --objeto_consumidor
	'2375', --objeto_proveedor
	'actualizacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000176', --dep_id
	'2370', --objeto_consumidor
	'1832000197', --objeto_proveedor
	'c25_capacitacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000001', --dep_id
	'2370', --objeto_consumidor
	'1832000002', --objeto_proveedor
	'cargos', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000003', --dep_id
	'2370', --objeto_consumidor
	'1832000004', --objeto_proveedor
	'categorizacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000181', --dep_id
	'2370', --objeto_consumidor
	'1832000201', --objeto_proveedor
	'cursos_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 1832

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1272', --dep_id
	'2370', --objeto_consumidor
	'2399', --objeto_proveedor
	'docec_facultad', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1274', --dep_id
	'2370', --objeto_consumidor
	'2402', --objeto_proveedor
	'docec_posgrado', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1248', --dep_id
	'2370', --objeto_consumidor
	'2363', --objeto_proveedor
	'edicion_ficha', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1282', --dep_id
	'2370', --objeto_consumidor
	'2418', --objeto_proveedor
	'formaciion_docec', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1247', --dep_id
	'2370', --objeto_consumidor
	'2369', --objeto_proveedor
	'formacion_academica', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000065', --dep_id
	'2370', --objeto_consumidor
	'1832000065', --objeto_proveedor
	'formacion_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000041', --dep_id
	'2370', --objeto_consumidor
	'1832000040', --objeto_proveedor
	'formacion_vinc', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000076', --dep_id
	'2370', --objeto_consumidor
	'1832000080', --objeto_proveedor
	'gestion_catedra', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000074', --dep_id
	'2370', --objeto_consumidor
	'1832000078', --objeto_proveedor
	'gobierno_depar', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000072', --dep_id
	'2370', --objeto_consumidor
	'1832000076', --objeto_proveedor
	'gobierno_inst', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000070', --dep_id
	'2370', --objeto_consumidor
	'1832000074', --objeto_proveedor
	'gobierno_univ', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000008', --dep_id
	'2370', --objeto_consumidor
	'1832000008', --objeto_proveedor
	'impacto_pub', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000029', --dep_id
	'2370', --objeto_consumidor
	'1832000028', --objeto_proveedor
	'libros_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000051', --dep_id
	'2370', --objeto_consumidor
	'1832000051', --objeto_proveedor
	'libros_extension_632', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000083', --dep_id
	'2370', --objeto_consumidor
	'1832000087', --objeto_proveedor
	'libros_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 1832

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1345', --dep_id
	'2370', --objeto_consumidor
	'2478', --objeto_proveedor
	'licencias', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1283', --dep_id
	'2370', --objeto_consumidor
	'2419', --objeto_proveedor
	'materiales_pedag', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000023', --dep_id
	'2370', --objeto_consumidor
	'1832000022', --objeto_proveedor
	'part_comite', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000089', --dep_id
	'2370', --objeto_consumidor
	'1832000093', --objeto_proveedor
	'part_divulg_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000087', --dep_id
	'2370', --objeto_consumidor
	'1832000091', --objeto_proveedor
	'part_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000021', --dep_id
	'2370', --objeto_consumidor
	'1832000020', --objeto_proveedor
	'part_reun_cientificas', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000060', --dep_id
	'2370', --objeto_consumidor
	'1832000060', --objeto_proveedor
	'participacion_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000045', --dep_id
	'2370', --objeto_consumidor
	'1832000045', --objeto_proveedor
	'participacion_vinc', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000019', --dep_id
	'2370', --objeto_consumidor
	'1832000018', --objeto_proveedor
	'patentes', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000174', --dep_id
	'2370', --objeto_consumidor
	'1832000192', --objeto_proveedor
	'premios_doc', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000062', --dep_id
	'2370', --objeto_consumidor
	'1832000062', --objeto_proveedor
	'premios_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000091', --dep_id
	'2370', --objeto_consumidor
	'1832000095', --objeto_proveedor
	'premios_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000037', --dep_id
	'2370', --objeto_consumidor
	'1832000036', --objeto_proveedor
	'premios_vinc_internac', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000043', --dep_id
	'2370', --objeto_consumidor
	'1832000042', --objeto_proveedor
	'promocion_vinc', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000006', --dep_id
	'2370', --objeto_consumidor
	'1832000006', --objeto_proveedor
	'proy_acreditados', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000025', --dep_id
	'2370', --objeto_consumidor
	'1832000024', --objeto_proveedor
	'proy_acreditados_vinc', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 1832

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1278', --dep_id
	'2370', --objeto_consumidor
	'2410', --objeto_proveedor
	'proy_educativos', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000047', --dep_id
	'2370', --objeto_consumidor
	'1832000047', --objeto_proveedor
	'proy_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000078', --dep_id
	'2370', --objeto_consumidor
	'1832000082', --objeto_proveedor
	'proy_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000010', --dep_id
	'2370', --objeto_consumidor
	'1832000010', --objeto_proveedor
	'publ_rev_cientificas', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000013', --dep_id
	'2370', --objeto_consumidor
	'1832000012', --objeto_proveedor
	'publ_rev_divulgacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000049', --dep_id
	'2370', --objeto_consumidor
	'1832000049', --objeto_proveedor
	'publ_rev_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000081', --dep_id
	'2370', --objeto_consumidor
	'1832000085', --objeto_proveedor
	'publ_rev_gestion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000028', --dep_id
	'2370', --objeto_consumidor
	'1832000027', --objeto_proveedor
	'publ_rev_vinculacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000039', --dep_id
	'2370', --objeto_consumidor
	'1832000038', --objeto_proveedor
	'reconocimientos', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000058', --dep_id
	'2370', --objeto_consumidor
	'1832000058', --objeto_proveedor
	'registros_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000035', --dep_id
	'2370', --objeto_consumidor
	'1832000034', --objeto_proveedor
	'registros_vinculacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000182', --dep_id
	'2370', --objeto_consumidor
	'1832000203', --objeto_proveedor
	'representacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 1832

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1276', --dep_id
	'2370', --objeto_consumidor
	'2407', --objeto_proveedor
	'reu_cientificas', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000179', --dep_id
	'2370', --objeto_consumidor
	'1832000199', --objeto_proveedor
	'rrhh_investigacion', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000066', --dep_id
	'2370', --objeto_consumidor
	'1832000066', --objeto_proveedor
	'servicios_extension', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
INSERT INTO apex_objeto_dependencias (proyecto, dep_id, objeto_consumidor, objeto_proveedor, identificador, parametros_a, parametros_b, parametros_c, inicializar, orden) VALUES (
	'pruebas', --proyecto
	'1832000188', --dep_id
	'2370', --objeto_consumidor
	'1832000210', --objeto_proveedor
	'traer_anterior', --identificador
	NULL, --parametros_a
	NULL, --parametros_b
	NULL, --parametros_c
	NULL, --inicializar
	NULL  --orden
);
--- FIN Grupo de desarrollo 1832

------------------------------------------------------------
-- apex_objeto_ci_pantalla
------------------------------------------------------------

--- INICIO Grupo de desarrollo 0
INSERT INTO apex_objeto_ci_pantalla (objeto_ci_proyecto, objeto_ci, pantalla, identificador, orden, etiqueta, descripcion, tip, imagen_recurso_origen, imagen, objetos, eventos, subclase, subclase_archivo, template, template_impresion, punto_montaje) VALUES (
	'pruebas', --objeto_ci_proyecto
	'2370', --objeto_ci
	'1218', --pantalla
	'pant_inicial', --identificador
	'2', --orden
	'Actividades en docencia', --etiqueta
	NULL, --descripcion
	NULL, --tip
	'apex', --imagen_recurso_origen
	NULL, --imagen
	NULL, --objetos
	NULL, --eventos
	NULL, --subclase
	NULL, --subclase_archivo
	'', --template
	NULL, --template_impresion
	'13'  --punto_montaje
);
INSERT INTO apex_objeto_ci_pantalla (objeto_ci_proyecto, objeto_ci, pantalla, identificador, orden, etiqueta, descripcion, tip, imagen_recurso_origen, imagen, objetos, eventos, subclase, subclase_archivo, template, template_impresion, punto_montaje) VALUES (
	'pruebas', --objeto_ci_proyecto
	'2370', --objeto_ci
	'1219', --pantalla
	'pant_investigacion', --identificador
	'3', --orden
	'Investigación', --etiqueta
	NULL, --descripcion
	NULL, --tip
	'apex', --imagen_recurso_origen
	NULL, --imagen
	NULL, --objetos
	NULL, --eventos
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --template
	NULL, --template_impresion
	'13'  --punto_montaje
);
INSERT INTO apex_objeto_ci_pantalla (objeto_ci_proyecto, objeto_ci, pantalla, identificador, orden, etiqueta, descripcion, tip, imagen_recurso_origen, imagen, objetos, eventos, subclase, subclase_archivo, template, template_impresion, punto_montaje) VALUES (
	'pruebas', --objeto_ci_proyecto
	'2370', --objeto_ci
	'1220', --pantalla
	'pant_gestion', --identificador
	'6', --orden
	'Gestión', --etiqueta
	NULL, --descripcion
	NULL, --tip
	'apex', --imagen_recurso_origen
	NULL, --imagen
	NULL, --objetos
	NULL, --eventos
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --template
	NULL, --template_impresion
	'13'  --punto_montaje
);
INSERT INTO apex_objeto_ci_pantalla (objeto_ci_proyecto, objeto_ci, pantalla, identificador, orden, etiqueta, descripcion, tip, imagen_recurso_origen, imagen, objetos, eventos, subclase, subclase_archivo, template, template_impresion, punto_montaje) VALUES (
	'pruebas', --objeto_ci_proyecto
	'2370', --objeto_ci
	'1221', --pantalla
	'pant_extension', --identificador
	'5', --orden
	'Extensión', --etiqueta
	NULL, --descripcion
	NULL, --tip
	'apex', --imagen_recurso_origen
	NULL, --imagen
	NULL, --objetos
	NULL, --eventos
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --template
	NULL, --template_impresion
	'13'  --punto_montaje
);
INSERT INTO apex_objeto_ci_pantalla (objeto_ci_proyecto, objeto_ci, pantalla, identificador, orden, etiqueta, descripcion, tip, imagen_recurso_origen, imagen, objetos, eventos, subclase, subclase_archivo, template, template_impresion, punto_montaje) VALUES (
	'pruebas', --objeto_ci_proyecto
	'2370', --objeto_ci
	'1222', --pantalla
	'pant_vinculacion', --identificador
	'4', --orden
	'Vinculación', --etiqueta
	'Descripcion en pantalla vinculacion', --descripcion
	NULL, --tip
	'apex', --imagen_recurso_origen
	NULL, --imagen
	NULL, --objetos
	NULL, --eventos
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --template
	NULL, --template_impresion
	'13'  --punto_montaje
);
--- FIN Grupo de desarrollo 0

--- INICIO Grupo de desarrollo 1832
INSERT INTO apex_objeto_ci_pantalla (objeto_ci_proyecto, objeto_ci, pantalla, identificador, orden, etiqueta, descripcion, tip, imagen_recurso_origen, imagen, objetos, eventos, subclase, subclase_archivo, template, template_impresion, punto_montaje) VALUES (
	'pruebas', --objeto_ci_proyecto
	'2370', --objeto_ci
	'1832000029', --pantalla
	'pant_datos', --identificador
	'1', --orden
	'Datos personales', --etiqueta
	NULL, --descripcion
	NULL, --tip
	'apex', --imagen_recurso_origen
	NULL, --imagen
	NULL, --objetos
	NULL, --eventos
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --template
	NULL, --template_impresion
	'13'  --punto_montaje
);
INSERT INTO apex_objeto_ci_pantalla (objeto_ci_proyecto, objeto_ci, pantalla, identificador, orden, etiqueta, descripcion, tip, imagen_recurso_origen, imagen, objetos, eventos, subclase, subclase_archivo, template, template_impresion, punto_montaje) VALUES (
	'pruebas', --objeto_ci_proyecto
	'2370', --objeto_ci
	'1832000031', --pantalla
	'pant_traer_anterior', --identificador
	'7', --orden
	'Importar anterior', --etiqueta
	NULL, --descripcion
	NULL, --tip
	'apex', --imagen_recurso_origen
	NULL, --imagen
	NULL, --objetos
	NULL, --eventos
	NULL, --subclase
	NULL, --subclase_archivo
	NULL, --template
	NULL, --template_impresion
	'13'  --punto_montaje
);
--- FIN Grupo de desarrollo 1832

------------------------------------------------------------
-- apex_objetos_pantalla
------------------------------------------------------------
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1218', --pantalla
	'2370', --objeto_ci
	'0', --orden
	'1252'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1218', --pantalla
	'2370', --objeto_ci
	'2', --orden
	'1272'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1218', --pantalla
	'2370', --objeto_ci
	'3', --orden
	'1274'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1218', --pantalla
	'2370', --objeto_ci
	'4', --orden
	'1276'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1218', --pantalla
	'2370', --objeto_ci
	'5', --orden
	'1278'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1218', --pantalla
	'2370', --objeto_ci
	'6', --orden
	'1282'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1218', --pantalla
	'2370', --objeto_ci
	'7', --orden
	'1283'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1218', --pantalla
	'2370', --objeto_ci
	'1', --orden
	'1832000174'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1219', --pantalla
	'2370', --objeto_ci
	'0', --orden
	'1832000003'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1219', --pantalla
	'2370', --objeto_ci
	'1', --orden
	'1832000006'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1219', --pantalla
	'2370', --objeto_ci
	'3', --orden
	'1832000010'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1219', --pantalla
	'2370', --objeto_ci
	'4', --orden
	'1832000013'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1219', --pantalla
	'2370', --objeto_ci
	'5', --orden
	'1832000016'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1219', --pantalla
	'2370', --objeto_ci
	'6', --orden
	'1832000021'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1219', --pantalla
	'2370', --objeto_ci
	'7', --orden
	'1832000023'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1219', --pantalla
	'2370', --objeto_ci
	'2', --orden
	'1832000179'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1220', --pantalla
	'2370', --objeto_ci
	'0', --orden
	'1832000070'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1220', --pantalla
	'2370', --objeto_ci
	'1', --orden
	'1832000072'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1220', --pantalla
	'2370', --objeto_ci
	'2', --orden
	'1832000074'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1220', --pantalla
	'2370', --objeto_ci
	'3', --orden
	'1832000076'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1220', --pantalla
	'2370', --objeto_ci
	'4', --orden
	'1832000078'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1220', --pantalla
	'2370', --objeto_ci
	'5', --orden
	'1832000081'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1220', --pantalla
	'2370', --objeto_ci
	'6', --orden
	'1832000083'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1220', --pantalla
	'2370', --objeto_ci
	'8', --orden
	'1832000087'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1220', --pantalla
	'2370', --objeto_ci
	'9', --orden
	'1832000089'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1220', --pantalla
	'2370', --objeto_ci
	'10', --orden
	'1832000091'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1220', --pantalla
	'2370', --objeto_ci
	'11', --orden
	'1832000182'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1221', --pantalla
	'2370', --objeto_ci
	'0', --orden
	'1832000047'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1221', --pantalla
	'2370', --objeto_ci
	'2', --orden
	'1832000049'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1221', --pantalla
	'2370', --objeto_ci
	'3', --orden
	'1832000051'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1221', --pantalla
	'2370', --objeto_ci
	'5', --orden
	'1832000060'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1221', --pantalla
	'2370', --objeto_ci
	'6', --orden
	'1832000062'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1221', --pantalla
	'2370', --objeto_ci
	'7', --orden
	'1832000065'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1221', --pantalla
	'2370', --objeto_ci
	'8', --orden
	'1832000066'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1221', --pantalla
	'2370', --objeto_ci
	'1', --orden
	'1832000181'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1222', --pantalla
	'2370', --objeto_ci
	'4', --orden
	'1832000019'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1222', --pantalla
	'2370', --objeto_ci
	'0', --orden
	'1832000025'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1222', --pantalla
	'2370', --objeto_ci
	'1', --orden
	'1832000028'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1222', --pantalla
	'2370', --objeto_ci
	'2', --orden
	'1832000029'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1222', --pantalla
	'2370', --objeto_ci
	'5', --orden
	'1832000037'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1222', --pantalla
	'2370', --objeto_ci
	'6', --orden
	'1832000039'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1222', --pantalla
	'2370', --objeto_ci
	'7', --orden
	'1832000041'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1222', --pantalla
	'2370', --objeto_ci
	'8', --orden
	'1832000043'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1222', --pantalla
	'2370', --objeto_ci
	'9', --orden
	'1832000045'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1832000029', --pantalla
	'2370', --objeto_ci
	'3', --orden
	'1247'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1832000029', --pantalla
	'2370', --objeto_ci
	'0', --orden
	'1248'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1832000029', --pantalla
	'2370', --objeto_ci
	'2', --orden
	'1345'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1832000029', --pantalla
	'2370', --objeto_ci
	'1', --orden
	'1832000001'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1832000029', --pantalla
	'2370', --objeto_ci
	'4', --orden
	'1832000176'  --dep_id
);
INSERT INTO apex_objetos_pantalla (proyecto, pantalla, objeto_ci, orden, dep_id) VALUES (
	'pruebas', --proyecto
	'1832000031', --pantalla
	'2370', --objeto_ci
	'0', --orden
	'1832000188'  --dep_id
);

------------------------------------------------------------
-- apex_eventos_pantalla
------------------------------------------------------------
INSERT INTO apex_eventos_pantalla (pantalla, objeto_ci, evento_id, proyecto) VALUES (
	'1218', --pantalla
	'2370', --objeto_ci
	'1832000014', --evento_id
	'pruebas'  --proyecto
);
INSERT INTO apex_eventos_pantalla (pantalla, objeto_ci, evento_id, proyecto) VALUES (
	'1219', --pantalla
	'2370', --objeto_ci
	'1832000014', --evento_id
	'pruebas'  --proyecto
);
INSERT INTO apex_eventos_pantalla (pantalla, objeto_ci, evento_id, proyecto) VALUES (
	'1220', --pantalla
	'2370', --objeto_ci
	'1832000014', --evento_id
	'pruebas'  --proyecto
);
INSERT INTO apex_eventos_pantalla (pantalla, objeto_ci, evento_id, proyecto) VALUES (
	'1221', --pantalla
	'2370', --objeto_ci
	'1832000014', --evento_id
	'pruebas'  --proyecto
);
INSERT INTO apex_eventos_pantalla (pantalla, objeto_ci, evento_id, proyecto) VALUES (
	'1222', --pantalla
	'2370', --objeto_ci
	'1832000014', --evento_id
	'pruebas'  --proyecto
);
INSERT INTO apex_eventos_pantalla (pantalla, objeto_ci, evento_id, proyecto) VALUES (
	'1832000029', --pantalla
	'2370', --objeto_ci
	'1832000014', --evento_id
	'pruebas'  --proyecto
);
