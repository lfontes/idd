<?php
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

}
?>