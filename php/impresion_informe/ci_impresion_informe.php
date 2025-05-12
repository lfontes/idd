<?php
class ci_impresion_informe extends pruebas_ci
{
	protected $s__datos_filtro;
	protected $s__id_ficha_seleccionada;
	protected $s__seleccion;


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

	function evt__cuadro__seleccion($datos)
	{
		$this->dep('datos')->cargar($datos);
				
	}

	function evt__cuadro__imprimir($datos)
	{
		$this->dep('datos')->cargar($datos);
		$this->s__seleccion = $this->dep('cuadro')->get_clave_seleccionada();
		
		
	}

	//---- Formulario -------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
		if ($this->dep('datos')->esta_cargada()) {
			$form->set_datos($this->dep('datos')->tabla('ficha')->get());
		}
	}

	function evt__formulario__alta($datos)
	{
		$this->dep('datos')->tabla('ficha')->set($datos);
		$this->dep('datos')->sincronizar();
		$this->resetear();
	}

	function evt__formulario__modificacion($datos)
	{
		$this->dep('datos')->tabla('ficha')->set($datos);
		$this->dep('datos')->sincronizar();
		$this->resetear();
	}

	function evt__formulario__baja()
	{
		$this->dep('datos')->eliminar_todo();
		$this->resetear();
	}

	function evt__formulario__cancelar()
	{
		$this->resetear();
	}

	function resetear()
	{
		$this->dep('datos')->resetear();
	}

	function vista_jasperreports(toba_vista_jasperreports $vista) 
	{
		$path= toba::memoria()->get_parametro('path');
		$data_cuadro=$this->dep('cuadro')->get_datos();
		$ficha= $data_cuadro[$path]['id'];

		
		// Parámetros para el informe
		$titulo = 'Informe de Labor';
		$vista->set_parametro('titulo', 'S', $titulo);
		
		$vista->set_parametro('ficha_id', 'E', $ficha);
		
		$vista->set_path_reporte('/var/local/pruebas/vendor/siu-toba/framework/proyectos/pruebas/reportes/report2.jasper');

		/* Parámetros para el informe
		$vista->set_parametros(array(
			'id_ficha' => $this->s__id_ficha,
			'usuario' => toba::usuario()->get_id()
		));

		// Configurar salida en PDF
		$vista->set_salida('pdf');

		// Nombre del archivo generado
		$vista->set_nombre_archivo('informe_fichas.pdf');
		*/
	}

	/**
		* Atrapa el evento seleccion del cuadro e invoca manualmente el serviccio vista_jasperreports pasandole el hash por parámetro
		* @param array $datos
		*/
	
	 function extender_objeto_js()
	{ 
		if ($this->get_id_pantalla() == 'pant_edicion') {
			echo 
				 toba::escaper()->escapeJs($this->dep('cuadro')->objeto_js).".evt__imprimir = function(params) {
					
					location.href = vinculador.get_url(null, null, 'vista_jasperreports', {'path': params});
					return false;
				}
				
			"; 
			
		} 
	} 

}
?>