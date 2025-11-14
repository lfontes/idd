<?php

use datos\dependencias;

class ci_interno extends pruebas_ci
{

	/**
	 * devuelve el usuario logueado
	 */
	function usuario()
	{
		return toba::usuario()->get_id();
	}
	//-----------------------------------------------------------------------------------
	//---- edicion_ficha ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__edicion_ficha(pruebas_ei_formulario $form)
	{
		$filas = $this->controlador()->get_tabla('ficha')->get_cantidad_filas();
		if ($filas > 0) {
			$form->set_datos($this->controlador()->get_tabla('ficha')->get());
		}
	}

	function evt__edicion_ficha__modificacion($datos)
	{
		$datos['fecha_modif']= date('Y-m-d');
		$this->controlador()->get_tabla('ficha')->set($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- formacion_academica ----------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formacion_academica(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('formacion_academica')->get_filas());
	}

	function evt__formacion_academica__modificacion($datos)
	{
		$this->controlador()->get_tabla('formacion_academica')->procesar_filas($datos);
	}

	/**
	 * Se ejecuta por �nica vez cuando el componente entra en la operaci�n.
	 * Es �til por ejemplo para inicializar un conjunto de variables de sesion y evitar el chequeo continuo de las mismas
	 * Hay situaciones en las que su ejecuci�n no coincide con el instante inicial de operaci�n:
	 *  - Si el componente es un ci dentro de otro ci, reci�n se ejecuta cuando entra a la operacion que no necesariamente es al inicio, si por ejemplo se encuentra en la 3er pantalla del ci principal.
	 *  - Si se ejecuta una limpieza de memoria (comportamiento por defecto del evt__cancelar)
	 */
	function ini__operacion() {}

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Ventana de extension para ejecutar controles antes de entrar a la pagina.
	 * Se ejecuta luego de lanzar los eventos del ci.
	 * Si se lanza una excepcion se evita el cambio de pantalla.
	 * [wiki:Referencia/Objetos/ci#Controlandolaentradaylasalida Ver m�s]
	 */
	function evt__pant_inicial__entrada() {}

	//-----------------------------------------------------------------------------------
	//---- actualizacion ----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Permite cambiar la configuraci�n del ML previo a la generaci�n de la salida
	 * El formato debe ser una matriz array('id_fila' => array('id_ef' => valor, ...), ...)
	 */


	function conf__actualizacion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('actualizacion')->get_filas());
	}

	function evt__actualizacion__modificacion($datos)
	{

		$this->controlador()->get_tabla('actualizacion')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- Premios docentes ------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__premios_doc(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('premios_docencia')->get_filas());
	}

	function evt__premios_doc__modificacion($datos)
	{
		$this->controlador()->get_tabla('premios_docencia')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- cargos ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cargos(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('cargos')->get_filas());
	}

	function evt__cargos__modificacion($datos)
	{
		
	$this->controlador()->get_tabla('cargos')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- licencias -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__licencias(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('licencias')->get_filas());
	}

	function evt__licencias__modificacion($datos)
	{
		$this->controlador()->get_tabla('licencias')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- 2.5 Capacitacions -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__c25_capacitacion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('capacitacion')->get_filas());
	}

	function evt__c25_capacitacion__modificacion($datos)
	{
	
		$this->controlador()->get_tabla('capacitacion')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- docec_facultad ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__docec_facultad(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('docec_facultad')->get_filas());
	}

	function evt__docec_facultad__modificacion($datos)
	{
		$this->controlador()->get_tabla('docec_facultad')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- docec_posgrado ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__docec_posgrado(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('docec_posgrado')->get_filas());
	}

	function evt__docec_posgrado__modificacion($datos)
	{
		$this->controlador()->get_tabla('docec_posgrado')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- reu_cientificas --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__reu_cientificas(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('reu_cientificas')->get_filas());
	}

	function evt__reu_cientificas__modificacion($datos)
	{
		$this->controlador()->get_tabla('reu_cientificas')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- proy_educativos --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__proy_educativos(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('proy_educativos')->get_filas());
	}

	function evt__proy_educativos__modificacion($datos)
	{
		$this->controlador()->get_tabla('proy_educativos')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- formaciion_docec -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formaciion_docec(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('formacion_docec')->get_filas());
	}

	function evt__formaciion_docec__modificacion($datos)
	{
		$this->controlador()->get_tabla('formacion_docec')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- materiales_pedag -------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__materiales_pedag(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('materiales_pedag')->get_filas());
	}

	function evt__materiales_pedag__modificacion($datos)
	{
		$this->controlador()->get_tabla('materiales_pedag')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- 4.1 Categorizacion investigacion ---------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__categorizacion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('categorizacion_inv')->get_filas());
	}

	function evt__categorizacion__modificacion($datos)
	{
		$this->controlador()->get_tabla('categorizacion_inv')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- 4.2 Proyectos acreditados ----------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__proy_acreditados(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('proy_acreditados')->get_filas());
	}
	function evt__proy_acreditados__modificacion($datos)
	{
		$this->controlador()->get_tabla('proy_acreditados')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- 4.3 Formacion RRHH investigacion ---------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__rrhh_investigacion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('rrhh_investigacion')->get_filas());
	}
	function evt__rrhh_investigacion__modificacion($datos)
	{
		$this->controlador()->get_tabla('rrhh_investigacion')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- 4.4.1 Publicaciones en revistas científicas ----------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__publ_rev_cientificas(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('publ_rev_cientificas')->get_filas());
	}
	function evt__publ_rev_cientificas__modificacion($datos)
	{
		$this->controlador()->get_tabla('publ_rev_cientificas')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- 4.4.2 Publicaciones en revistas de divulgación--------------------------------
	//-----------------------------------------------------------------------------------

	function conf__publ_rev_divulgacion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('publ_rev_divulgacion')->get_filas());
	}
	function evt__publ_rev_divulgacion__modificacion($datos)
	{
		$this->controlador()->get_tabla('publ_rev_divulgacion')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- 4.4.3.1 Libros completos -----------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__Libros(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('libros')->get_filas());
	}
	function evt__Libros__modificacion($datos)
	{
		$this->controlador()->get_tabla('libros')->procesar_filas($datos);
	}
	
	

	//-----------------------------------------------------------------------------------
	//---- 4.6 Participacion en reuniones científicas -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__part_reun_cientificas(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('part_reun_cientificas')->get_filas());
	}
	function evt__part_reun_cientificas__modificacion($datos)
	{
		$this->controlador()->get_tabla('part_reun_cientificas')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 4.7 Participacion en comite editorial ----------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__part_comite(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('part_comite')->get_filas());
	}
	function evt__part_comite__modificacion($datos)
	{
		$this->controlador()->get_tabla('part_comite')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 5.1 Proyectos acreditados en vinculación -------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__proy_acreditados_vinc(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('proy_acreditados_vinc')->get_filas());
	}
	function evt__proy_acreditados_vinc__modificacion($datos)
	{
		$this->controlador()->get_tabla('proy_acreditados_vinc')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 5.2.1 Publicaciones revistas divulgación -------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__publ_rev_vinculacion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('publ_rev_vinculacion')->get_filas());
	}
	function evt__publ_rev_vinculacion__modificacion($datos)
	{
		$this->controlador()->get_tabla('publ_rev_vinculacion')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 5.2.2 Publicaciones libros extension -------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__libros_extension(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('libros_extension')->get_filas());
	}
	function evt__libros_extension__modificacion($datos)
	{
		$this->controlador()->get_tabla('libros_extension')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 5.2.3 capitulos libros vinculación -------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cap_libros_vinculacion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('cap_libros_vinculacion')->get_filas());
	}
	function evt__cap_libros_vinculacion__modificacion($datos)
	{
		$this->controlador()->get_tabla('cap_libros_vinculacion')->procesar_filas($datos);
	}

	//-----------------------------------------------------------------------------------
	//---- 5.2.4 Patentes -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	 function conf__patentes(pruebas_ei_formulario_ml $form_ml)
	 {
	 	$form_ml->set_datos($this->controlador()->get_tabla('patentes_vinculacion')->get_filas());
	 }
	 function evt__patentes__modificacion($datos)
	 {
	 	$this->controlador()->get_tabla('patentes_vinculacion')->procesar_filas($datos);
	 }

	//-----------------------------------------------------------------------------------
	//---- 5.2.5 Registros vinculación --------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__registros_vinculacion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('registros_vinculacion')->get_filas());
	}
	function evt__registros_vinculacion__modificacion($datos)
	{
		$this->controlador()->get_tabla('registros_vinculacion')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 5.3 premios y distinciones Internacionalización ------------------------------
	//-----------------------------------------------------------------------------------

	function conf__premios_vinc_internac(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('premios_vinc_internac')->get_filas());
	}
	function evt__premios_vinc_internac__modificacion($datos)
	{
		$this->controlador()->get_tabla('premios_vinc_internac')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 5.3. Reconocimientos ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__reconocimientos(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('reconocimientos')->get_filas());
	}
	function evt__reconocimientos__modificacion($datos)
	{
		$this->controlador()->get_tabla('reconocimientos')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 5.4 Formacion RRHH en vinculación ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formacion_vinc(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('formacion_vinc')->get_filas());
	}
	function evt__formacion_vinc__modificacion($datos)
	{
		$this->controlador()->get_tabla('formacion_vinc')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 5.4 Promocion vinculación ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__promocion_vinc(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('promocion_vinc')->get_filas());
	}
	function evt__promocion_vinc__modificacion($datos)
	{
		$this->controlador()->get_tabla('promocion_vinc')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 5.4 Participacion vinculación ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__participacion_vinc(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('participacion_vinc')->get_filas());
	}
	function evt__participacion_vinc__modificacion($datos)
	{
		$this->controlador()->get_tabla('participacion_vinc')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 6.1 Proyectos extension ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__proy_extension(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('proy_extension')->get_filas());
	}
	function evt__proy_extension__modificacion($datos)
	{
		$this->controlador()->get_tabla('proy_extension')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 6.2 Publicacion revistas extension ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__publ_rev_extension(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('publ_rev_extension')->get_filas());
	}
	function evt__publ_rev_extension__modificacion($datos)
	{
		$this->controlador()->get_tabla('publ_rev_extension')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 6.3.2 Publicacion libros extension ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__libros_extension_632(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('libros_extension_632')->get_filas());
	}
	function evt__libros_extension_632__modificacion($datos)
	{
		$this->controlador()->get_tabla('libros_extension_632')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 6.3.3 Capitulos libros extension ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cap_libros_extension(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('cap_libros_extension')->get_filas());
	}
	function evt__cap_libros_extension__modificacion($datos)
	{
		$this->controlador()->get_tabla('cap_libros_extension')->procesar_filas($datos);
	}
	
	//-----------------------------------------------------------------------------------
	//---- 6.3.5 Registros extension ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__registros_extension(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('registros_extension')->get_filas());
	}
	function evt__registros_extension__modificacion($datos)
	{
		$this->controlador()->get_tabla('registros_extension')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 6.4 Participacion reuniones extension ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__participacion_extension(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('part_extension')->get_filas());
	}
	function evt__participacion_extension__modificacion($datos)
	{
		$this->controlador()->get_tabla('part_extension')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 6.5 Premios Internacionalización extension ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__premios_extension(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('premios_extension')->get_filas());
	}
	function evt__premios_extension__modificacion($datos)
	{
		$this->controlador()->get_tabla('premios_extension')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 6.6 Formacion en extension ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formacion_extension(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('formacion_extension')->get_filas());
	}
	function evt__formacion_extension__modificacion($datos)
	{
		$this->controlador()->get_tabla('formacion_extension')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 6.7 Servicios extension ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__servicios_extension(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('servicios_extension')->get_filas());
	}
	function evt__servicios_extension__modificacion($datos)
	{
		$this->controlador()->get_tabla('servicios_extension')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 6.8 Cursos de extension dictados ---------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cursos_extension(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('cursos_extension')->get_filas());
	}
	function evt__cursos_extension__modificacion($datos)
	{
		$this->controlador()->get_tabla('cursos_extension')->procesar_filas($datos);
	}



	//-----------------------------------------------------------------------------------
	//---- 7.1 Gobierno universitario ---------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__gobierno_univ(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('gobierno_univ')->get_filas());
	}
	function evt__gobierno_univ__modificacion($datos)
	{
		$this->controlador()->get_tabla('gobierno_univ')->procesar_filas($datos);
	}
	//-------------------------------------------------------------------------------
	//---- 7.2 Gobierno institucional   -------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__gobierno_inst(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('gobierno_inst')->get_filas());
	}
	function evt__gobierno_inst__modificacion($datos)
	{
		$this->controlador()->get_tabla('gobierno_inst')->procesar_filas($datos);
	}
	//-------------------------------------------------------------------------------
	//---- 7.3 Gobierno departamental   -------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__gobierno_depar(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('gobierno_depar')->get_filas());
	}
	function evt__gobierno_depar__modificacion($datos)
	{
		$this->controlador()->get_tabla('gobierno_depar')->procesar_filas($datos);
	}
	//-------------------------------------------------------------------------------
	//---- 7.4 Gestión catedra   -------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__gestion_catedra(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('gestion_catedra')->get_filas());
	}
	function evt__gestion_catedra__modificacion($datos)
	{
		$this->controlador()->get_tabla('gestion_catedra')->procesar_filas($datos);
	}
	//-------------------------------------------------------------------------------
	//---- 7.5 Proyectos acteditados Gestión-------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__proy_gestion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('proy_gestion')->get_filas());
	}
	function evt__proy_gestion__modificacion($datos)
	{
		$this->controlador()->get_tabla('proy_gestion')->procesar_filas($datos);
	}
	//-------------------------------------------------------------------------------
	//---- 7.6.1 Publicacion revistas Gestión-------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__publ_rev_gestion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('publ_rev_gestion')->get_filas());
	}
	function evt__publ_rev_gestion__modificacion($datos)
	{
		$this->controlador()->get_tabla('publ_rev_gestion')->procesar_filas($datos);
	}
	//-------------------------------------------------------------------------------
	//---- 7.6.2 Libros Gestión-------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__libros_gestion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('libros_gestion')->get_filas());
	}
	function evt__libros_gestion__modificacion($datos)
	{
		$this->controlador()->get_tabla('libros_gestion')->procesar_filas($datos);
	}
	//-------------------------------------------------------------------------------
	//---- 7.6.3 Capítulos en Libros Gestión-------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cap_libros_gestion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('cap_libros_gestion')->get_filas());
	}
	function evt__cap_libros_gestion__modificacion($datos)
	{
		$this->controlador()->get_tabla('cap_libros_gestion')->procesar_filas($datos);
	}
	//-------------------------------------------------------------------------------
	//---- 7.7 Participacion en congreso y jornadas Gestión ----------------------------
	//-----------------------------------------------------------------------------------

	function conf__part_gestion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('part_gestion')->get_filas());
	}
	function evt__part_gestion__modificacion($datos)
	{
		$this->controlador()->get_tabla('part_gestion')->procesar_filas($datos);
	}
	//-------------------------------------------------------------------------------
	//---- 7.8 Participacion divulgacion Gestión ----------------------------
	//-----------------------------------------------------------------------------------

	function conf__part_divulg_gestion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('part_divulg_gestion')->get_filas());
	}
	function evt__part_divulg_gestion__modificacion($datos)
	{
		$this->controlador()->get_tabla('part_divulg_gestion')->procesar_filas($datos);
	}
	//-------------------------------------------------------------------------------
	//---- 7.9 Premios Gestión ----------------------------
	//-----------------------------------------------------------------------------------

	function conf__premios_gestion(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('premios_gestion')->get_filas());
	}
	function evt__premios_gestion__modificacion($datos)
	{
		$this->controlador()->get_tabla('premios_gestion')->procesar_filas($datos);
	}


	
	/** // Método AJAX
	 * Devuelve la cantidad de inscriptos por año y actividad.
	 * @param int $anio_academico
	 * @param string $codigo_actividad
	 * @return array
	 */


	function ajax__get_inscriptos_por_espacio($parametros, toba_ajax_respuesta $respuesta)
	{
		//$anio = (string)$parametros[0];
		$anio = '2024';
		$espacio = toba::db()->quote($parametros[0]);

		$sql = "
        SELECT count(*) as inscriptos
        FROM negocio.vw_insc_cursada
        JOIN negocio.sga_comisiones ON negocio.vw_insc_cursada.comision = negocio.sga_comisiones.comision
        JOIN negocio.sga_periodos_lectivos ON negocio.sga_comisiones.periodo_lectivo = negocio.sga_periodos_lectivos.periodo_lectivo
        JOIN negocio.sga_periodos ON negocio.sga_periodos_lectivos.periodo = negocio.sga_periodos.periodo
        JOIN negocio.vw_actividades_plan ON (
            negocio.sga_comisiones.elemento = negocio.vw_actividades_plan.elemento
            AND negocio.vw_insc_cursada.plan_version = negocio.vw_actividades_plan.plan_version
        )
        WHERE negocio.sga_periodos.anio_academico = '2024'
          AND negocio.vw_actividades_plan.codigo = $espacio
    ";

		$cant_inscriptos = toba::db('guarani')->consultar($sql);
		$respuesta->set($cant_inscriptos[0]['inscriptos']);
	}


	//IMPRESION
	//-----------------------------------------------------------------------------------
	//----  PDF --------------------------------------------------------------------------		
	function vista_pdf(toba_vista_pdf $salida)
	{
		//Cambio lo márgenes accediendo directamente a la librería PDF
		$pdf = $salida->get_pdf();
		$pdf->ezSetMargins(80, 50, 50, 50);	//top, bottom, left, right

		//Pie de página
		$formato = 'Página {PAGENUM} de {TOTALPAGENUM}';
		$pdf->ezStartPageNumbers(300, 20, 8, 'left', $formato, 1);	//x, y, size, pos, texto, pagina inicio

		//Inserto los componentes usando la API de toba_vista_pdf

		// $salida->titulo($this->get_nombre());
		$salida->titulo('INFORME INTEGRADOR DE EVALUACIÓN ANUAL DE DESEMPEÑO DOCENTE DE LA FACULTAD DE CIENCIAS AGRARIAS UNCUYO');
		//$salida->mensaje('Nota: Este es el Principal');
		$salida->titulo('I. Datos personales');
		$salida->separacion();
		$this->dependencia('edicion_ficha')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('cargos')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('licencias')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('formacion_academica')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('c25_capacitacion')->vista_pdf($salida);
		$salida->separacion();
		$salida->titulo('III. Docencia');
		$salida->separacion();
	
		$this->dependencia('actualizacion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('premios_doc')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('docec_facultad')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('docec_posgrado')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('reu_cientificas')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('proy_educativos')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('formaciion_docec')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('materiales_pedag')->vista_pdf($salida);
		$salida->separacion();
		$salida->titulo('IV. Investigacion');
		$this->dependencia('categorizacion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('proy_acreditados')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('rrhh_investigacion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('impacto_pub')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('publ_rev_cientificas')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('publ_rev_divulgacion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('Libros')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('part_reun_cientificas')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('part_comite')->vista_pdf($salida);
		$salida->separacion();
		$salida->titulo('V. Investigacion');
		$salida->separacion();
		$this->dependencia('proy_acreditados_vinc')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('publ_rev_vinculacion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('libros_extension')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('cap_libros_vinculacion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('patentes')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('registros_vinculacion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('premios_vinc_internac')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('reconocimientos')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('formacion_vinc')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('promocion_vinc')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('participacion_vinc')->vista_pdf($salida);
		$salida->separacion();
		$salida->titulo('VI- Actividades de Extensión');
		$salida->separacion();
		$this->dependencia('proy_extension')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('publ_rev_extension')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('libros_extension_632')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('cap_libros_extension')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('registros_extension')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('participacion_extension')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('premios_extension')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('formacion_extension')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('servicios_extension')->vista_pdf($salida);
		$salida->separacion();
		$salida->titulo('VII- Actividades de Gestión');
		$salida->separacion();
		$this->dependencia('gobierno_univ')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('gobierno_inst')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('gobierno_depar')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('gestion_catedra')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('proy_gestion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('publ_rev_gestion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('libros_gestion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('cap_libros_gestion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('part_gestion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('part_divulg_gestion')->vista_pdf($salida);
		$salida->separacion();
		$this->dependencia('premios_gestion')->vista_pdf($salida);
		$salida->separacion();


		//Encabezado
		$pdf = $salida->get_pdf();
		foreach ($pdf->ezPages as $pageNum => $id) {
			$pdf->reopenObject($id);
			$imagen = toba::proyecto()->get_path() . '/www/img/logo_Ciencias_Agrarias_UNCuyo.jpg';
			$pdf->addJpegFromFile($imagen, 50, 780, 141, 45);	//imagen, x, y, ancho, alto
			$pdf->closeObject();
		}
	}
}
