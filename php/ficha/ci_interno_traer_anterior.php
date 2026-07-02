<?php

/**
 * Helper del Informe de Labor Docente para "traer datos del año anterior".
 *
 * Busca la ficha del año anterior más cercano del mismo docente y clona
 * en memoria las filas de las secciones (tablas hijas) elegidas, para que
 * el docente las revise/edite antes de guardar. La persistencia real ocurre
 * recién en el evt__guardar (sincronizar) del CI padre (ci_ficha).
 *
 * Se instancia on-demand desde ci_interno (mismo patrón que ci_interno_impresion_pdf).
 */
class ci_interno_traer_anterior
{
	protected $ci;

	/**
	 * Lista blanca: id_tabla (identificador en la relación de datos) => etiqueta visible.
	 * Solo secciones cuyos datos suelen repetirse de un año a otro.
	 * Se excluyen a propósito las que cambian cada período: 'cargos' y 'licencias'.
	 * Para habilitar/deshabilitar una sección, alcanza con editar este array.
	 */
	protected static $secciones_arrastrables = array(
		// 1 - Datos generales
		'formacion_academica'    => '1. Formación académica',
		'actualizacion'          => '1. Actualización',
		'premios_docencia'       => '1. Premios docentes',
		// 2 - Capacitación
		'capacitacion'           => '2.5 Capacitación',
		// 3 - Docencia
		'docec_facultad'         => '3. Docencia de grado (facultad)',
		'docec_posgrado'         => '3. Docencia de posgrado',
		'reu_cientificas'        => '3. Reuniones científicas (docencia)',
		'proy_educativos'        => '3. Proyectos educativos',
		'formacion_docec'        => '3. Formación docente',
		'materiales_pedag'       => '3. Materiales pedagógicos',
		// 4 - Investigación
		'categorizacion_inv'     => '4.1 Categorización en investigación',
		'proy_acreditados'       => '4.2 Proyectos acreditados',
		'rrhh_investigacion'     => '4.3 Formación RRHH en investigación',
		'publ_rev_cientificas'   => '4.4.1 Publicaciones en revistas científicas',
		'publ_rev_divulgacion'   => '4.4.2 Publicaciones en revistas de divulgación',
		'libros'                 => '4.4.3.1 Libros completos',
		'part_reun_cientificas'  => '4.6 Participación en reuniones científicas',
		'part_comite'            => '4.7 Participación en comité editorial',
		// 5 - Vinculación
		'proy_acreditados_vinc'  => '5.1 Proyectos acreditados en vinculación',
		'publ_rev_vinculacion'   => '5.2.1 Publicaciones en revistas (vinculación)',
		'libros_extension'       => '5.2.2 Libros (vinculación)',
		'cap_libros_vinculacion' => '5.2.3 Capítulos de libros (vinculación)',
		'patentes_vinculacion'   => '5.2.4 Patentes',
		'registros_vinculacion'  => '5.2.5 Registros (vinculación)',
		'premios_vinc_internac'  => '5.3 Premios y distinciones (internacionalización)',
		'reconocimientos'        => '5.3 Reconocimientos',
		'formacion_vinc'         => '5.4 Formación RRHH en vinculación',
		'promocion_vinc'         => '5.4 Promoción (vinculación)',
		'participacion_vinc'     => '5.4 Participación (vinculación)',
		// 6 - Extensión
		'proy_extension'         => '6.1 Proyectos de extensión',
		'publ_rev_extension'     => '6.2 Publicaciones en revistas (extensión)',
		'libros_extension_632'   => '6.3.2 Libros (extensión)',
		'cap_libros_extension'   => '6.3.3 Capítulos de libros (extensión)',
		'registros_extension'    => '6.3.5 Registros (extensión)',
		'part_extension'         => '6.4 Participación en reuniones (extensión)',
		'premios_extension'      => '6.5 Premios (extensión)',
		'formacion_extension'    => '6.6 Formación en extensión',
		'servicios_extension'    => '6.7 Servicios de extensión',
		'cursos_extension'       => '6.8 Cursos de extensión dictados',
		// 7 - Gestión / Gobierno
		'gobierno_univ'          => '7.1 Gobierno universitario',
		'gobierno_inst'          => '7.2 Gobierno institucional',
		'gobierno_depar'         => '7.3 Gobierno departamental',
		'gestion_catedra'        => '7.4 Gestión de cátedra',
		'proy_gestion'           => '7.5 Proyectos acreditados (gestión)',
		'publ_rev_gestion'       => '7.6.1 Publicaciones en revistas (gestión)',
		'libros_gestion'         => '7.6.2 Libros (gestión)',
		'cap_libros_gestion'     => '7.6.3 Capítulos de libros (gestión)',
		'part_gestion'           => '7.7 Participación en congresos y jornadas (gestión)',
		'part_divulg_gestion'    => '7.8 Participación en divulgación (gestión)',
		'premios_gestion'        => '7.9 Premios (gestión)',
		'representacion'         => '7.10 Representación',
	);

	function __construct($ci)
	{
		$this->ci = $ci;
	}

	protected function controlador()
	{
		return $this->ci->controlador();
	}

	/**
	 * Devuelve la lista blanca (id_tabla => etiqueta), lista para poblar
	 * las opciones del ef de selección múltiple del pop-up.
	 */
	function get_secciones_arrastrables()
	{
		return self::$secciones_arrastrables;
	}

	function es_seccion_arrastrable($id_tabla)
	{
		return array_key_exists($id_tabla, self::$secciones_arrastrables);
	}

	/**
	 * Datos de la ficha actualmente cargada en la relación.
	 * @return array|null null si no hay ficha cargada.
	 */
	protected function get_ficha_actual()
	{
		$tabla_ficha = $this->controlador()->get_tabla('ficha');
		if ($tabla_ficha->get_cantidad_filas() == 0) {
			return null;
		}
		return $tabla_ficha->get();
	}

	/**
	 * Busca el id de la ficha del año anterior más cercano del mismo docente
	 * (mismo legajo, año estrictamente menor al de la ficha actual).
	 * @return int|null
	 */
	function get_ficha_anio_anterior($ficha = null)
	{
		if ($ficha === null) {
			$ficha = $this->get_ficha_actual();
		}
		if (empty($ficha) || ! isset($ficha['anio']) || ! isset($ficha['legajo_doc'])) {
			return null;
		}
		$anio = (int) $ficha['anio'];
		$legajo = quote($ficha['legajo_doc']);
		$sql = "SELECT id
				FROM ficha
				WHERE legajo_doc = $legajo
				  AND anio < $anio
				ORDER BY anio DESC
				LIMIT 1";
		$rs = toba::db('desempenio')->consultar($sql);
		return ! empty($rs) ? (int) $rs[0]['id'] : null;
	}

	/**
	 * ¿Existe un informe de un año anterior para el docente de la ficha actual?
	 */
	function hay_anio_anterior()
	{
		return $this->get_ficha_anio_anterior() !== null;
	}

	/**
	 * Clona en memoria las filas de una sección desde la ficha del año anterior.
	 *
	 * @param string $id_tabla    identificador de la tabla hija en la relación.
	 * @param bool   $reemplazar  si true, vacía primero las filas actuales de esa tabla.
	 * @return int   cantidad de filas traídas.
	 */
	function traer_una($id_tabla, $reemplazar = false)
	{
		if (! $this->es_seccion_arrastrable($id_tabla)) {
			return 0;
		}
		$ficha = $this->get_ficha_actual();
		if (empty($ficha)) {
			throw new toba_error_usuario('Debe guardar la ficha antes de traer datos del año anterior.');
		}
		$ficha_anterior = $this->get_ficha_anio_anterior($ficha);
		if ($ficha_anterior === null) {
			return 0;
		}

		$origen = toba::db('desempenio')->consultar(
			"SELECT * FROM " . $this->controlador()->get_tabla($id_tabla)->get_tabla() . " WHERE ficha_id = " . intval($ficha_anterior));

		$tabla = $this->controlador()->get_tabla($id_tabla);
		if ($reemplazar) {
			$tabla->eliminar_filas();
		}

		$traidas = 0;
		foreach ($origen as $fila) {
			unset($fila['id']);                 // nueva PK vía secuencia al sincronizar
			$fila['ficha_id'] = $ficha['id'];   // se cuelga de la ficha actual
			if (array_key_exists('anio', $fila)) {
				$fila['anio'] = $ficha['anio'];
			}
			$tabla->nueva_fila($fila);
			$traidas++;
		}
		return $traidas;
	}

	/**
	 * Trae varias secciones de una sola pasada (usado por el pop-up).
	 *
	 * @param array $ids_tablas  identificadores elegidos por el docente.
	 * @param bool  $reemplazar
	 * @return int  total de filas traídas entre todas las secciones.
	 */
	function traer_secciones(array $ids_tablas, $reemplazar = false)
	{
		$total = 0;
		foreach ($ids_tablas as $id_tabla) {
			$total += $this->traer_una($id_tabla, $reemplazar);
		}
		return $total;
	}
}
