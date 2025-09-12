<?php
use datos\dependencias;
class ci_interno extends pruebas_ci
{
	
	/**
	 * devuelve el usuario logueado
	 */
	function usuario() {
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
	function ini__operacion()
	{
	}

	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	/**
	 * Ventana de extension para ejecutar controles antes de entrar a la pagina.
	 * Se ejecuta luego de lanzar los eventos del ci.
	 * Si se lanza una excepcion se evita el cambio de pantalla.
	 * [wiki:Referencia/Objetos/ci#Controlandolaentradaylasalida Ver m�s]
	 */
	function evt__pant_inicial__entrada()
	{
	}

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
	//---- 4.3 Impacto publicaciones ----------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__impacto_pub(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('impacto_pub')->get_filas());
	}
	function evt__impacto_pub__modificacion($datos)
	{
		$this->controlador()->get_tabla('impacto_pub')->procesar_filas($datos);
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
	//---- 4.4.3.2 Capitulos de Libros --------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cap_libros(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('cap_libros')->get_filas());
	}
	function evt__cap_libros__modificacion($datos)
	{
		$this->controlador()->get_tabla('cap_libros')->procesar_filas($datos);
	}
	//-----------------------------------------------------------------------------------
	//---- 4.5 Patentes -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__patentes(pruebas_ei_formulario_ml $form_ml)
	{
		$form_ml->set_datos($this->controlador()->get_tabla('patentes')->get_filas());
	}
	function evt__patentes__modificacion($datos)
	{
		$this->controlador()->get_tabla('patentes')->procesar_filas($datos);
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
	
	/** // Método AJAX
     * Devuelve la cantidad de inscriptos por año y actividad.
     * @param int $anio_academico
     * @param string $codigo_actividad
     * @return array
     */

	
    function ajax__get_inscriptos_por_espacio($parametros, toba_ajax_respuesta $respuesta)
    {
		//$anio = (string)$parametros[0];
		$anio='2024';
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

	
}
?>