<?php
class ci_ficha extends pruebas_ci
{
	protected $s__datos_filtro;
	protected $s__ficha_seleccionada;

	// funciones para acceder a los datos desde el ci interno
	// asi es mas corto para llamarlas desde el ci interno 
	function get_relacion()
	{
		return $this->dep('datos');
	}

	function get_tabla($id_tabla)
	{
		return $this->get_relacion()->tabla($id_tabla);
	}

	//---- Filtro -----------------------------------------------------------------------

	function conf__filtro(toba_ei_formulario $filtro)
	{
		if (isset($this->s__datos_filtro)) {
			$filtro->set_datos($this->s__datos_filtro);
		}
	}

	function evt__filtro__filtrar($datos)
	{
		$this->s__datos_filtro = $datos;
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__datos_filtro);
	}

	//---- Cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		if (isset($this->s__datos_filtro)) {
			$cuadro->set_datos($this->dep('datos')->tabla('ficha')->get_listado($this->s__datos_filtro));
		} else {
			$cuadro->set_datos($this->dep('datos')->tabla('ficha')->get_listado());
		}
		
	}

	function evt__cuadro__eliminar($datos)
	{
		$this->dep('datos')->resetear();
		$this->dep('datos')->cargar($datos);
		$this->dep('datos')->eliminar_todo();
		$this->dep('datos')->resetear();
	}

	function evt__cuadro__seleccion($datos)
	{
		$this->dep('datos')->cargar($datos);
		$this->s__ficha_seleccionada = $seleccion;
		$this->set_pantalla('pant_edicion');
	}

	
	//---- Formulario -------------------------------------------------------------------

	// function conf__formulario(toba_ei_formulario $form)
	// {
	// 	if ($this->dep('datos')->esta_cargada()) {
	// 		$form->set_datos($this->dep('datos')->tabla('ficha')->get());
	// 	} else {
	// 		$this->pantalla()->eliminar_evento('eliminar');
	// 	}
	// }

	// function evt__formulario__modificacion($datos)
	// {
	// 	$this->dep('datos')->tabla('ficha')->set($datos);
	// }

	function resetear()
	{
		$this->dep('datos')->resetear();
		$this->set_pantalla('pant_seleccion');
	}

	//---- EVENTOS CI -------------------------------------------------------------------

	function evt__agregar()
	{
		$this->set_pantalla('pant_edicion');
	}

	function evt__volver()
	{
		$this->resetear();
	}

	function evt__eliminar()
	{
		$this->dep('datos')->eliminar_todo();
		$this->resetear();
	}

	function evt__guardar()
	{
		$this->dep('datos')->sincronizar();
		$this->resetear();
	}

	function ini__operacion() {}

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
		if ($this->dep('datos')->esta_cargada()) {
			$form->set_datos($this->dep('datos')->tabla('ficha')->get());
		} else {
			$this->pantalla()->eliminar_evento('eliminar');
		}
	}

	function evt__edicion_ficha__modificacion($datos)
	{
		$this->dep('datos')->tabla('ficha')->set($datos);
	}


	//-----------------------------------------------------------------------------------
	//---- cuadro_idd -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__cuadro_idd(pruebas_ei_cuadro $cuadro_idd)
	{
		// $sql = "SELECT * FROM vista_idd 
		// 		join ficha on (ficha.id = vista_idd.ficha_id)
		// 		ORDER BY ficha_id";

		// $datos = toba::db('desempenio')->consultar($sql);
		// $cuadro->set_datos($datos);
		if (isset($this->s__datos_filtro)) {
			$cuadro_idd->set_datos($this->dep('datos')->tabla('ficha')->get_listado($this->s__datos_filtro));
		} else {
			$cuadro_idd->set_datos($this->dep('datos')->tabla('ficha')->get_listado());
		}
	}

//IMPRESION

//funcion que devuelte la ficha seleccionada  a partir del indice del cuadro $seleccion
	function set_ficha_seleccionada($seleccion)
	{
		$r_fichas=($this->dep('cuadro')->get_datos());
		return $r_fichas[$seleccion];
	}

	function vista_jasperreports(toba_vista_jasperreports $vista)
	{
		$id = toba::memoria()->get_parametro('seleccion'); // Obtiene el id de la ficha seleccionada desde la memoria
		// Parámetros para el informe
		$titulo = 'Informe de Fichas';
		$vista->set_parametro('titulo', 'S', $titulo);
		$fichaid =  $this->set_ficha_seleccionada($id); // Obtiene la ficha seleccionada a partir del id
		
		$vista->set_parametro('ficha_id', 'E', $fichaid['id']);

		$vista->set_path_reporte('/var/local/pruebas/vendor/siu-toba/framework/proyectos/pruebas/reportes/report2.jasper');
	}


	/**
	 * Atrapa el evento seleccion del cuadro e invoca manualmente el serviccio vista_jasperreports pasandole el hash por parámetro
	 */

	function extender_objeto_js()
	{
		if ($this->get_id_pantalla() == 'pant_seleccion') {

			echo
			toba::escaper()->escapeJs($this->dep('cuadro')->objeto_js) . ".evt__imprimir = function(params) {
	 				location.href = vinculador.get_url(null, null, 'vista_jasperreports', {'seleccion': params});
	 				return false;
	 			}
	 		";
		}
	}




	//-----------------------------------------------------------------------------------
	//---- cuadro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__cuadro__imprimir($seleccion)
	{
		$this->s__id_ficha = $seleccion;
		$vista = new toba_vista_jasperreports();
		$this->vista_jasperreports($vista);
	}
}
