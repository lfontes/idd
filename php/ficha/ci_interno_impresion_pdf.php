<?php

class ci_interno_impresion_pdf
{
	protected $ci;

	function __construct($ci)
	{
		$this->ci = $ci;
	}

	protected function controlador()
	{
		return $this->ci->controlador();
	}

	protected function dependencia($id)
	{
		return $this->ci->dependencia($id);
	}

	function get_nombre_docente_pdf($dni_doc)
	{
		$dni_doc = preg_replace('/\D+/', '', (string) $dni_doc);
		if ($dni_doc === '') {
			return '';
		}

		$sql_agente = "SELECT apellido, nombre FROM public.agentes WHERE dni = " . quote($dni_doc) . " LIMIT 1";
		$agente = toba::db('desempenio')->consultar($sql_agente);
		if (! empty($agente)) {
			$apellido = trim((string) $agente[0]['apellido']);
			$nombre = trim((string) $agente[0]['nombre']);
			return trim($apellido . ', ' . $nombre, ', ');
		}

		$sql_docente = "SELECT ayn FROM docentes WHERE dni = " . quote($dni_doc) . " LIMIT 1";
		$docente = toba::db('desempenio')->consultar($sql_docente);
		if (! empty($docente)) {
			return trim((string) $docente[0]['ayn']);
		}

		return '';
	}

	function get_descripciones_combo_pdf($tabla, $columna_desc)
	{
		$sql = "SELECT id, $columna_desc AS descripcion FROM $tabla ORDER BY id";
		$filas = toba::db('desempenio')->consultar($sql);
		$descripciones = array();
		foreach ($filas as $fila) {
			$descripciones[(string) $fila['id']] = $fila['descripcion'];
		}
		return $descripciones;
	}

	function log_memoria_pdf($seccion)
	{
		$actual = round(memory_get_usage(true) / 1048576, 2);
		$pico = round(memory_get_peak_usage(true) / 1048576, 2);
		error_log('[PDF ILD] ' . $seccion . ' | memoria=' . $actual . 'MB | pico=' . $pico . 'MB');
	}

	function imprimir_reu_cientificas_tabla_pdf(toba_vista_pdf $salida)
	{
		$filas = $this->controlador()->get_tabla('reu_cientificas')->get_filas();
		$tipos_participacion = $this->get_descripciones_combo_pdf('c35_tipos_participacion', 'tipo_participacion');
		$tipos_presentacion = $this->get_descripciones_combo_pdf('c35_tipos_presentacion', 'tipo_presentacion');

		$datos_tabla = array();
		foreach ($filas as $fila) {
			$tipo_participacion = (string) $fila['tipo_participacion_id'];
			$tipo_presentacion = (string) $fila['present_tipo_id'];
			$nombre = trim((string) $fila['nombre']);
			$titulo = trim((string) $fila['titulo']);

			if ($nombre !== '') {
				$nombre = wordwrap($nombre, 40, "\n", true);
			}
			if ($titulo !== '') {
				$titulo = wordwrap($titulo, 34, "\n", true);
			}

			$datos_tabla[] = array(
				'nombre' => $nombre,
				'titulo' => $titulo,
				'fecha' => $fila['fecha'],
				'tipo_participacion' => isset($tipos_participacion[$tipo_participacion]) ? $tipos_participacion[$tipo_participacion] : $fila['tipo_participacion_id'],
				'tipo_presentacion' => isset($tipos_presentacion[$tipo_presentacion]) ? $tipos_presentacion[$tipo_presentacion] : $fila['present_tipo_id'],
			);
		}

		$datos = array(
			'titulo_tabla' => '3.5 Participacion en reuniones con referencia a la docencia',
			'titulos_columnas' => array(
				'nombre' => 'Reunion',
				'titulo' => 'Titulo',
				'fecha' => 'Fecha',
				'tipo_participacion' => 'Participacion',
				'tipo_presentacion' => 'Presentacion',
			),
			'datos_tabla' => $datos_tabla,
		);

		$salida->tabla(
			$datos,
			true,
			7,
			array(
				'rowGap' => 2,
				'titleFontSize' => 11,
				'xPos' => 'left',
				'maxWidth' => $salida->get_ancho(100),
				'width' => 490,
				'cols' => array(
					'nombre' => array('width' => 180),
					'titulo' => array('width' => 125),
					'fecha' => array('width' => 55),
					'tipo_participacion' => array('width' => 60),
					'tipo_presentacion' => array('width' => 70),
				),
			)
		);
	}

	function imprimir_part_reun_cientificas_tabla_pdf(toba_vista_pdf $salida)
	{
		$filas = $this->controlador()->get_tabla('part_reun_cientificas')->get_filas();

		$datos_tabla = array();
		foreach ($filas as $fila) {
			$tipo = trim((string) $fila['tipo']);
			$nombre = trim((string) $fila['nombre']);
			$lugar = trim((string) $fila['lugar']);
			$caracter = trim((string) $fila['caracter']);
			$participacion = trim((string) $fila['participacion']);

			if ($tipo !== '') {
				$tipo = wordwrap($tipo, 14, "\n", true);
			}
			if ($nombre !== '') {
				$nombre = wordwrap($nombre, 34, "\n", true);
			}
			if ($lugar !== '') {
				$lugar = wordwrap($lugar, 18, "\n", true);
			}
			if ($caracter !== '') {
				$caracter = wordwrap($caracter, 16, "\n", true);
			}
			if ($participacion !== '') {
				$participacion = wordwrap($participacion, 18, "\n", true);
			}

			$datos_tabla[] = array(
				'tipo' => $tipo,
				'nombre' => $nombre,
				'lugar' => $lugar,
				'fecha' => $fila['fecha'],
				'caracter' => $caracter,
				'participacion' => $participacion,
			);
		}

		$datos = array(
			'titulo_tabla' => '4.5 Participacion en eventos cientificos y/o investigacion',
			'titulos_columnas' => array(
				'tipo' => 'Tipo',
				'nombre' => 'Nombre',
				'lugar' => 'Lugar',
				'fecha' => 'Fecha',
				'caracter' => 'Caracter',
				'participacion' => 'Participacion',
			),
			'datos_tabla' => $datos_tabla,
		);

		$salida->tabla(
			$datos,
			true,
			7,
			array(
				'rowGap' => 2,
				'titleFontSize' => 11,
				'xPos' => 'left',
				'width' => 490,
				'maxWidth' => $salida->get_ancho(100),
				'cols' => array(
					'tipo' => array('width' => 65),
					'nombre' => array('width' => 160),
					'lugar' => array('width' => 80),
					'fecha' => array('width' => 55),
					'caracter' => array('width' => 65),
					'participacion' => array('width' => 65),
				),
			)
		);
	}

	function imprimir_proy_acreditados_tabla_pdf(toba_vista_pdf $salida)
	{
		$filas = $this->controlador()->get_tabla('proy_acreditados')->get_filas();

		$datos_tabla = array();
		foreach ($filas as $fila) {
			$datos_tabla[] = array(
				'titulo' => $fila['titulo_proyecto'],
				'organismo' => $fila['organismo'],
				'tipo' => $fila['tipo_proyecto'],
				'participacion' => $fila['participacion'],
				'horas' => $fila['horas'],
			);
		}

		$datos = array(
			'titulo_tabla' => '4.2 Proyectos acreditados',
			'titulos_columnas' => array(
				'titulo' => 'Proyecto',
				'organismo' => 'Organismo',
				'tipo' => 'Tipo',
				'participacion' => 'Participacion',
				'horas' => 'Hs',
			),
			'datos_tabla' => $datos_tabla,
		);

		$salida->tabla(
			$datos,
			true,
			8,
			array(
				'rowGap' => 2,
				'titleFontSize' => 11,
				'xPos' => 'left',
				'maxWidth' => $salida->get_ancho(100),
				'width' => 490,
				'cols' => array(
					'titulo' => array('width' => 230),
					'organismo' => array('width' => 105),
					'tipo' => array('width' => 55),
					'participacion' => array('width' => 85),
					'horas' => array('width' => 35),
				),
			)
		);
	}

	function imprimir_publ_rev_cientificas_tabla_pdf(toba_vista_pdf $salida)
	{
		$filas = $this->controlador()->get_tabla('publ_rev_cientificas')->get_filas();

		$datos_tabla = array();
		foreach ($filas as $fila) {
			$datos_tabla[] = array(
				'titulo' => $fila['titulo'],
				'revista' => $fila['nombre_revista'],
				'indicador' => $fila['indicador_revista'],
				'indexada' => $fila['indexada'],
			);
		}

		$datos = array(
			'titulo_tabla' => '4.4.1 Publicacion en revistas cientificas',
			'titulos_columnas' => array(
				'titulo' => 'Titulo',
				'revista' => 'Revista',
				'indicador' => 'Indicador',
				'indexada' => 'Indexada',
			),
			'datos_tabla' => $datos_tabla,
		);

		$salida->tabla(
			$datos,
			true,
			8,
			array(
				'rowGap' => 2,
				'titleFontSize' => 11,
				'xPos' => 'left',
				'maxWidth' => $salida->get_ancho(100),
				'cols' => array(
					'indicador' => array('width' => 55),
					'indexada' => array('width' => 55),
				),
			)
		);
	}

	function imprimir_publ_rev_divulgacion_tabla_pdf(toba_vista_pdf $salida)
	{
		$filas = $this->controlador()->get_tabla('publ_rev_divulgacion')->get_filas();

		$datos_tabla = array();
		foreach ($filas as $fila) {
			$referencia = trim((string) $fila['ref_articulo']);
			if ($referencia !== '') {
				$referencia = wordwrap($referencia, 28, "\n", true);
			}

			$datos_tabla[] = array(
				'titulo' => $fila['titulo'],
				'autores' => $fila['autores'],
				'revista' => $fila['nombre_revista'],
				'ref_articulo' => $referencia,
			);
		}

		$datos = array(
			'titulo_tabla' => '4.4.2 Publicacion en revistas de divulgacion',
			'titulos_columnas' => array(
				'titulo' => 'Titulo',
				'autores' => 'Autores',
				'revista' => 'Revista',
				'ref_articulo' => 'Referencia',
			),
			'datos_tabla' => $datos_tabla,
		);

		$salida->tabla(
			$datos,
			true,
			7,
			array(
				'rowGap' => 2,
				'titleFontSize' => 11,
				'xPos' => 'left',
				'width' => 490,
				'maxWidth' => $salida->get_ancho(100),
				'cols' => array(
					'titulo' => array('width' => 155),
					'autores' => array('width' => 65),
					'revista' => array('width' => 155),
					'ref_articulo' => array('width' => 105),
				),
			)
		);
	}

	function imprimir_proy_acreditados_vinc_tabla_pdf(toba_vista_pdf $salida)
	{
		$filas = $this->controlador()->get_tabla('proy_acreditados_vinc')->get_filas();

		$datos_tabla = array();
		foreach ($filas as $fila) {
			$actividad = trim((string) $fila['actividad']);
			if ($actividad !== '') {
				$actividad = wordwrap($actividad, 24, "\n", true);
			}
			$acciones = trim((string) $fila['acciones']);
			if ($acciones !== '') {
				$acciones = wordwrap($acciones, 24, "\n", true);
			}

			$datos_tabla[] = array(
				'titulo_proy' => $fila['titulo_proy'],
				'organismo' => $fila['organismo'],
				'lugar' => $fila['lugar'],
				'participacion' => $fila['participacion'],
				'actividad' => $actividad,
				'acciones' => $acciones,
			);
		}

		$datos = array(
			'titulo_tabla' => '5.1 Proyectos acreditados en vinculacion',
			'titulos_columnas' => array(
				'titulo_proy' => 'Proyecto',
				'organismo' => 'Organismo',
				'lugar' => 'Lugar',
				'participacion' => 'Participacion',
				'actividad' => 'Actividad',
				'acciones' => 'Acciones',
			),
			'datos_tabla' => $datos_tabla,
		);

		$salida->tabla(
			$datos,
			true,
			7,
			array(
				'rowGap' => 2,
				'titleFontSize' => 11,
				'xPos' => 'left',
				'width' => 490,
				'maxWidth' => $salida->get_ancho(100),
				'cols' => array(
					'titulo_proy' => array('width' => 145),
					'organismo' => array('width' => 70),
					'lugar' => array('width' => 55),
					'participacion' => array('width' => 70),
					'actividad' => array('width' => 75),
					'acciones' => array('width' => 75),
				),
			)
		);
	}

	function imprimir_publ_rev_gestion_tabla_pdf(toba_vista_pdf $salida)
	{
		$filas = $this->controlador()->get_tabla('publ_rev_gestion')->get_filas();

		$datos_tabla = array();
		foreach ($filas as $fila) {
			$titulo = trim((string) $fila['titulo']);
			$autores = trim((string) $fila['autores']);
			$revista = trim((string) $fila['nombre_revista']);
			$referencia = trim((string) $fila['ref_articulo']);

			if ($titulo !== '') {
				$titulo = wordwrap($titulo, 34, "\n", true);
			}
			if ($autores !== '') {
				$autores = wordwrap($autores, 18, "\n", true);
			}
			if ($revista !== '') {
				$revista = wordwrap($revista, 30, "\n", true);
			}
			if ($referencia !== '') {
				$referencia = wordwrap($referencia, 22, "\n", true);
			}

			$datos_tabla[] = array(
				'titulo' => $titulo,
				'autores' => $autores,
				'revista' => $revista,
				'ref_articulo' => $referencia,
				'indexada' => $fila['indexada'],
			);
		}

		$datos = array(
			'titulo_tabla' => '7.6.1 Revista de Gestion',
			'titulos_columnas' => array(
				'titulo' => 'Titulo',
				'autores' => 'Autores',
				'revista' => 'Revista',
				'ref_articulo' => 'Referencia',
				'indexada' => 'Indexada',
			),
			'datos_tabla' => $datos_tabla,
		);

		$salida->tabla(
			$datos,
			true,
			7,
			array(
				'rowGap' => 2,
				'titleFontSize' => 11,
				'xPos' => 'left',
				'width' => 490,
				'maxWidth' => $salida->get_ancho(100),
				'cols' => array(
					'titulo' => array('width' => 145),
					'autores' => array('width' => 65),
					'revista' => array('width' => 135),
					'ref_articulo' => array('width' => 95),
					'indexada' => array('width' => 50),
				),
			)
		);
	}

	function imprimir_premios_vinc_internac_tabla_pdf(toba_vista_pdf $salida)
	{
		$filas = $this->controlador()->get_tabla('premios_vinc_internac')->get_filas();

		$datos_tabla = array();
		foreach ($filas as $fila) {
			$descripcion = trim((string) $fila['descripcion']);
			$red = trim((string) $fila['red_nombre']);
			$movilidad = trim((string) $fila['mov_nombre']);
			$lugar = trim((string) $fila['mov_lugar']);

			if ($descripcion !== '') {
				$descripcion = wordwrap($descripcion, 34, "\n", true);
			}
			if ($red !== '') {
				$red = wordwrap($red, 18, "\n", true);
			}
			if ($movilidad !== '') {
				$movilidad = wordwrap($movilidad, 18, "\n", true);
			}
			if ($lugar !== '') {
				$lugar = wordwrap($lugar, 16, "\n", true);
			}

			$datos_tabla[] = array(
				'descripcion' => $descripcion,
				'periodo' => $fila['periodo'],
				'red_nombre' => $red,
				'mov_nombre' => $movilidad,
				'mov_lugar' => $lugar,
			);
		}

		$datos = array(
			'titulo_tabla' => '5.4 Internacionalizacion de la vinculacion, premios y distinciones',
			'titulos_columnas' => array(
				'descripcion' => 'Descripcion',
				'periodo' => 'Periodo',
				'red_nombre' => 'Red',
				'mov_nombre' => 'Movilidad',
				'mov_lugar' => 'Lugar',
			),
			'datos_tabla' => $datos_tabla,
		);

		$salida->tabla(
			$datos,
			true,
			8,
			array(
				'rowGap' => 2,
				'titleFontSize' => 11,
				'xPos' => 'left',
				'width' => 490,
				'maxWidth' => $salida->get_ancho(100),
				'cols' => array(
					'descripcion' => array('width' => 175),
					'periodo' => array('width' => 55),
					'red_nombre' => array('width' => 90),
					'mov_nombre' => array('width' => 110),
					'mov_lugar' => array('width' => 60),
				),
			)
		);
	}

	function imprimir_participacion_vinc_tabla_pdf(toba_vista_pdf $salida)
	{
		$filas = $this->controlador()->get_tabla('participacion_vinc')->get_filas();

		$datos_tabla = array();
		foreach ($filas as $fila) {
			$nombre = trim((string) $fila['nombre']);
			if ($nombre !== '') {
				$nombre = wordwrap($nombre, 24, "\n", true);
			}
			$lugar = trim((string) $fila['lugar']);
			if ($lugar !== '') {
				$lugar = wordwrap($lugar, 18, "\n", true);
			}
			$descripcion = trim((string) $fila['descripcion']);
			if ($descripcion !== '') {
				$descripcion = wordwrap($descripcion, 42, "\n", true);
			}
			$tipo = trim((string) $fila['tipo']);
			if ($tipo !== '') {
				$tipo = wordwrap($tipo, 16, "\n", true);
			}

			$datos_tabla[] = array(
				'nombre' => $nombre,
				'lugar' => $lugar,
				'descripcion' => $descripcion,
				'tipo' => $tipo,
			);
		}

		$datos = array(
			'titulo_tabla' => '5.8 Participacion en eventos de vinculacion',
			'titulos_columnas' => array(
				'nombre' => 'Nombre evento',
				'lugar' => 'Lugar',
				'descripcion' => 'Descripcion',
				'tipo' => 'Tipo',
			),
			'datos_tabla' => $datos_tabla,
		);

		$salida->tabla(
			$datos,
			true,
			7,
			array(
				'rowGap' => 2,
				'titleFontSize' => 11,
				'xPos' => 'left',
				'width' => 490,
				'maxWidth' => $salida->get_ancho(100),
				'cols' => array(
					'nombre' => array('width' => 120),
					'lugar' => array('width' => 75),
					'descripcion' => array('width' => 220),
					'tipo' => array('width' => 75),
				),
			)
		);
	}

	function imprimir_proy_extension_tabla_pdf(toba_vista_pdf $salida)
	{
		$filas = $this->controlador()->get_tabla('proy_extension')->get_filas();

		$datos_tabla = array();
		foreach ($filas as $fila) {
			$datos_tabla[] = array(
				'nombre' => $fila['nombre'],
				'organismo' => $fila['organismo'],
				'lugar' => $fila['lugar'],
				'tipo_participacion' => $fila['tipo_participacion'],
			);
		}

		$datos = array(
			'titulo_tabla' => '6.1 Proyectos de extension',
			'titulos_columnas' => array(
				'nombre' => 'Proyecto',
				'organismo' => 'Organismo',
				'lugar' => 'Lugar',
				'tipo_participacion' => 'Participacion',
			),
			'datos_tabla' => $datos_tabla,
		);

		$salida->tabla(
			$datos,
			true,
			8,
			array(
				'rowGap' => 2,
				'titleFontSize' => 11,
				'xPos' => 'left',
				'maxWidth' => $salida->get_ancho(100),
				'cols' => array(
					'nombre' => array('width' => 220),
					'organismo' => array('width' => 120),
					'lugar' => array('width' => 90),
					'tipo_participacion' => array('width' => 65),
				),
			)
		);
	}

	function imprimir_cursos_extension_tabla_pdf(toba_vista_pdf $salida)
	{
		$filas = $this->controlador()->get_tabla('cursos_extension')->get_filas();
		$destinatarios_desc = array(
			'1' => 'Alumnos',
			'2' => 'Profesionales',
			'3' => 'Publico general',
			'4' => 'Otros',
		);

		$datos_tabla = array();
		foreach ($filas as $fila) {
			$destinatario = (string) $fila['destinatarios'];
			$datos_tabla[] = array(
				'denominacion' => $fila['denominacion'],
				'horas' => $fila['horas'],
				'destinatarios' => isset($destinatarios_desc[$destinatario]) ? $destinatarios_desc[$destinatario] : $fila['destinatarios'],
				'fecha_ini' => $fila['fecha_ini'],
				'fecha_fin' => $fila['fecha_fin'],
			);
		}

		$datos = array(
			'titulo_tabla' => '6.2 Cursos de capacitacion/actualizacion dictados',
			'titulos_columnas' => array(
				'denominacion' => 'Denominacion',
				'horas' => 'Hs',
				'destinatarios' => 'Destinatarios',
				'fecha_ini' => 'Fecha inicio',
				'fecha_fin' => 'Fecha fin',
			),
			'datos_tabla' => $datos_tabla,
		);

		$salida->tabla(
			$datos,
			true,
			8,
			array(
				'rowGap' => 2,
				'titleFontSize' => 11,
				'xPos' => 'left',
				'maxWidth' => $salida->get_ancho(100),
				'cols' => array(
					'denominacion' => array('width' => 210),
					'horas' => array('width' => 35),
					'destinatarios' => array('width' => 80),
					'fecha_ini' => array('width' => 65),
					'fecha_fin' => array('width' => 65),
				),
			)
		);
	}

	function imprimir_declaracion_jurada_pdf(toba_vista_pdf $salida)
	{
		$salida->salto_pagina();

		$fecha_impresion = date('d/m/Y');
		$texto_declaracion = "Fecha de impresion: $fecha_impresion\n\n";
		$texto_declaracion .= "Manifiesto la exactitud de los datos consignados tanto de la version impresa como de la electronica en cumplimiento a lo establecido por la Ordenanza N°91/2014-CS para la Evaluacion de Desempeno de los Docentes Efectivos de la Universidad Nacional de Cuyo y la Ordenanza N°591/2017-CD que reglamenta la evaluacion de desempeno de los Docentes Interinos de la Facultad de Ciencias Agrarias- UNCuyo.";

		$salida->tabla(
			array(
				'titulo_tabla' => 'DECLARACION JURADA',
				'datos_tabla' => array(
					array('texto' => $texto_declaracion),
				),
			),
			false,
			9,
			array(
				'xPos' => 'left',
				'maxWidth' => $salida->get_ancho(100),
				'cols' => array(
					'texto' => array('width' => $salida->get_ancho(100) - 10),
				),
			)
		);

		$salida->separacion(12);

		$firma = "\n\n\n................................................\nFirma                    Aclaracion";
		$salida->tabla(
			array(
				'titulos_columnas' => array(
					'docente' => 'DOCENTE',
					'superior' => 'AVAL DEL SUPERIOR',
				),
				'datos_tabla' => array(
					array(
						'docente' => $firma,
						'superior' => $firma,
					),
				),
			),
			true,
			9,
			array(
				'xPos' => 'left',
				'maxWidth' => $salida->get_ancho(100),
				'cols' => array(
					'docente' => array('width' => ($salida->get_ancho(100) / 2) - 3),
					'superior' => array('width' => ($salida->get_ancho(100) / 2) - 3),
				),
			)
		);
	}

	function vista_pdf(toba_vista_pdf $salida)
	{
		$this->log_memoria_pdf('inicio vista_pdf');
		$ficha = $this->controlador()->get_tabla('ficha')->get();
		$dni_doc = isset($ficha['dni_doc']) ? preg_replace('/\D+/', '', (string) $ficha['dni_doc']) : '';
		$nombre_docente = $this->get_nombre_docente_pdf($dni_doc);
		$nombre_archivo = ($dni_doc !== '') ? 'ILD-' . $dni_doc . '.pdf' : 'ILD.pdf';
		$salida->set_nombre_archivo($nombre_archivo);

		$pdf = $salida->get_pdf();
		$pdf->ezSetMargins(80, 50, 50, 50);

		$formato = 'Página {PAGENUM} de {TOTALPAGENUM}';
		$pdf->ezStartPageNumbers(300, 20, 8, 'left', $formato, 1);

		$salida->titulo('INFORME INTEGRADOR DE EVALUACIÓN ANUAL DE DESEMPEÑO DOCENTE DE LA FACULTAD DE CIENCIAS AGRARIAS UNCUYO');
		$salida->separacion();
		$salida->separacion();
		$salida->titulo('I. Datos personales');
		$salida->separacion();
		if ($nombre_docente !== '') {
			$salida->titulo('Docente: ' . $nombre_docente, 4);
			$salida->separacion();
		}
		$this->dependencia('edicion_ficha')->set_pdf_tabla_ancho('80%');
		$this->dependencia('edicion_ficha')->set_pdf_tabla_opciones(array(
			'xPos' => 'center',
			'xOrientation' => 'center',
		));
		$this->dependencia('edicion_ficha')->vista_pdf($salida);
		$this->log_memoria_pdf('despues edicion_ficha');
		$salida->separacion();
		$salida->salto_pagina();
		$this->dependencia('cargos')->vista_pdf($salida);
		$this->log_memoria_pdf('despues cargos');
		$salida->separacion();
		$this->dependencia('licencias')->vista_pdf($salida);
		$this->log_memoria_pdf('despues licencias');
		$salida->separacion();
		$this->dependencia('formacion_academica')->vista_pdf($salida);
		$this->log_memoria_pdf('despues formacion_academica');
		$salida->separacion();
		$this->dependencia('c25_capacitacion')->vista_pdf($salida);
		$this->log_memoria_pdf('despues c25_capacitacion');
		$salida->separacion();
		$salida->titulo('III. Docencia');
		$salida->separacion();

		$this->dependencia('actualizacion')->vista_pdf($salida);
		$this->log_memoria_pdf('despues actualizacion');
		$salida->separacion();
		$this->dependencia('premios_doc')->vista_pdf($salida);
		$this->log_memoria_pdf('despues premios_doc');
		$salida->separacion();
		$this->dependencia('docec_facultad')->vista_pdf($salida);
		$this->log_memoria_pdf('despues docec_facultad');
		$salida->separacion();
		$this->dependencia('docec_posgrado')->vista_pdf($salida);
		$this->log_memoria_pdf('despues docec_posgrado');
		$salida->separacion();
		$this->imprimir_reu_cientificas_tabla_pdf($salida);
		$this->log_memoria_pdf('despues reu_cientificas');
		$salida->separacion();
		$this->dependencia('proy_educativos')->vista_pdf($salida);
		$this->log_memoria_pdf('despues proy_educativos');
		$salida->separacion();
		$this->dependencia('formaciion_docec')->vista_pdf($salida);
		$this->log_memoria_pdf('despues formaciion_docec');
		$salida->separacion();
		$this->dependencia('materiales_pedag')->vista_pdf($salida);
		$this->log_memoria_pdf('despues materiales_pedag');
		$salida->separacion();
		$salida->titulo('IV. Investigacion');
		$this->dependencia('categorizacion')->vista_pdf($salida);
		$this->log_memoria_pdf('despues categorizacion');
		$salida->separacion();
		$this->imprimir_proy_acreditados_tabla_pdf($salida);
		$this->log_memoria_pdf('despues proy_acreditados');
		$salida->separacion();
		$this->dependencia('rrhh_investigacion')->vista_pdf($salida);
		$this->log_memoria_pdf('despues rrhh_investigacion');
		$salida->separacion();
		$this->dependencia('impacto_pub')->vista_pdf($salida);
		$this->log_memoria_pdf('despues impacto_pub');
		$salida->separacion();
		$this->imprimir_publ_rev_cientificas_tabla_pdf($salida);
		$this->log_memoria_pdf('despues publ_rev_cientificas');
		$salida->separacion();
		$this->imprimir_publ_rev_divulgacion_tabla_pdf($salida);
		$this->log_memoria_pdf('despues publ_rev_divulgacion');
		$salida->separacion();
		$this->dependencia('Libros')->vista_pdf($salida);
		$this->log_memoria_pdf('despues Libros');
		$salida->separacion();
		$this->imprimir_part_reun_cientificas_tabla_pdf($salida);
		$this->log_memoria_pdf('despues part_reun_cientificas');
		$salida->separacion();
		$this->dependencia('part_comite')->vista_pdf($salida);
		$this->log_memoria_pdf('despues part_comite');
		$salida->separacion();
		$salida->titulo('V. Investigacion');
		$salida->separacion();
		$this->imprimir_proy_acreditados_vinc_tabla_pdf($salida);
		$this->log_memoria_pdf('despues proy_acreditados_vinc');
		$salida->separacion();
		$this->dependencia('publ_rev_vinculacion')->vista_pdf($salida);
		$this->log_memoria_pdf('despues publ_rev_vinculacion');
		$salida->separacion();
		$this->dependencia('libros_extension')->vista_pdf($salida);
		$this->log_memoria_pdf('despues libros_extension');
		$salida->separacion();
		$this->dependencia('patentes')->vista_pdf($salida);
		$this->log_memoria_pdf('despues patentes');
		$salida->separacion();
		$this->dependencia('registros_vinculacion')->vista_pdf($salida);
		$this->log_memoria_pdf('despues registros_vinculacion');
		$salida->separacion();
		$this->imprimir_premios_vinc_internac_tabla_pdf($salida);
		$this->log_memoria_pdf('despues premios_vinc_internac');
		$salida->separacion();
		$this->dependencia('reconocimientos')->vista_pdf($salida);
		$this->log_memoria_pdf('despues reconocimientos');
		$salida->separacion();
		$this->dependencia('formacion_vinc')->vista_pdf($salida);
		$this->log_memoria_pdf('despues formacion_vinc');
		$salida->separacion();
		$this->dependencia('promocion_vinc')->vista_pdf($salida);
		$this->log_memoria_pdf('despues promocion_vinc');
		$salida->separacion();
		$this->imprimir_participacion_vinc_tabla_pdf($salida);
		$this->log_memoria_pdf('despues participacion_vinc');
		$salida->separacion();
		$salida->titulo('VI- Actividades de Extensión');
		$salida->separacion();
		$this->imprimir_proy_extension_tabla_pdf($salida);
		$this->log_memoria_pdf('despues proy_extension');
		$salida->separacion();
		$this->imprimir_cursos_extension_tabla_pdf($salida);
		$this->log_memoria_pdf('despues cursos_extension');
		$salida->separacion();
		$this->dependencia('publ_rev_extension')->vista_pdf($salida);
		$this->log_memoria_pdf('despues publ_rev_extension');
		$salida->separacion();
		$this->dependencia('libros_extension_632')->vista_pdf($salida);
		$this->log_memoria_pdf('despues libros_extension_632');
		$salida->separacion();
		$this->dependencia('registros_extension')->vista_pdf($salida);
		$this->log_memoria_pdf('despues registros_extension');
		$salida->separacion();
		$this->dependencia('participacion_extension')->vista_pdf($salida);
		$this->log_memoria_pdf('despues participacion_extension');
		$salida->separacion();
		$this->dependencia('premios_extension')->vista_pdf($salida);
		$this->log_memoria_pdf('despues premios_extension');
		$salida->separacion();
		$this->dependencia('formacion_extension')->vista_pdf($salida);
		$this->log_memoria_pdf('despues formacion_extension');
		$salida->separacion();
		$this->dependencia('servicios_extension')->vista_pdf($salida);
		$this->log_memoria_pdf('despues servicios_extension');
		$salida->separacion();
		$salida->titulo('VII- Actividades de Gestión');
		$salida->separacion();
		$this->dependencia('gobierno_univ')->vista_pdf($salida);
		$this->log_memoria_pdf('despues gobierno_univ');
		$salida->separacion();
		$this->dependencia('gobierno_inst')->vista_pdf($salida);
		$this->log_memoria_pdf('despues gobierno_inst');
		$salida->separacion();
		$this->dependencia('gobierno_depar')->vista_pdf($salida);
		$this->log_memoria_pdf('despues gobierno_depar');
		$salida->separacion();
		$this->dependencia('gestion_catedra')->vista_pdf($salida);
		$this->log_memoria_pdf('despues gestion_catedra');
		$salida->separacion();
		$this->dependencia('proy_gestion')->vista_pdf($salida);
		$this->log_memoria_pdf('despues proy_gestion');
		$salida->separacion();
		$this->imprimir_publ_rev_gestion_tabla_pdf($salida);
		$this->log_memoria_pdf('despues publ_rev_gestion');
		$salida->separacion();
		$this->dependencia('libros_gestion')->vista_pdf($salida);
		$this->log_memoria_pdf('despues libros_gestion');
		$salida->separacion();
		$this->dependencia('part_gestion')->vista_pdf($salida);
		$this->log_memoria_pdf('despues part_gestion');
		$salida->separacion();
		$this->dependencia('part_divulg_gestion')->vista_pdf($salida);
		$this->log_memoria_pdf('despues part_divulg_gestion');
		$salida->separacion();
		$this->dependencia('premios_gestion')->vista_pdf($salida);
		$this->log_memoria_pdf('despues premios_gestion');
		$salida->separacion();
		$this->dependencia('representacion')->vista_pdf($salida);
		$this->log_memoria_pdf('despues representacion');
		$salida->separacion();
		$this->imprimir_declaracion_jurada_pdf($salida);
		$this->log_memoria_pdf('despues declaracion_jurada');

		$pdf = $salida->get_pdf();
		foreach ($pdf->ezPages as $pageNum => $id) {
			$pdf->reopenObject($id);
			$imagen = toba::proyecto()->get_path() . '/www/img/logo_Ciencias_Agrarias_UNCuyo.jpg';
			$pdf->addJpegFromFile($imagen, 50, 780, 141, 45);
			$pdf->closeObject();
		}
		$this->log_memoria_pdf('fin vista_pdf');
	}
}
