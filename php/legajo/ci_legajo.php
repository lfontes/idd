<?php
class ci_legajo extends pruebas_ci
{
	protected $s__datos_filtro;
	


	//---- Filtro -----------------------------------------------------------------------

	function conf__filtro(toba_ei_formulario $filtro)
	{
		if (isset($this->s__datos_filtro)) {
			$filtro->set_datos($this->s__datos_filtro);
		}
	}

	function evt__filtro__filtrar($datos)
	{
		$datos['legajo'] = $this->legajo;
		$this->s__datos_filtro = $datos;
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__datos_filtro);
	}

	//---- Cuadro -----------------------------------------------------------------------

	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		if (isset($this->legajo)) {
		
			$this->s__datos_filtro['legajo'] = $this->legajo;

			$cuadro->set_datos($this->dep('datos')->tabla('agentes')->get_listado($this->s__datos_filtro));
		} else {
			$cuadro->set_datos($this->dep('datos')->tabla('agentes')->get_listado());
		}
	}

	function evt__cuadro__seleccion($datos)
	{
		$this->dep('datos')->cargar($datos);
		$this->set_pantalla('pant_edicion');
	}

	//---- Formulario -------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
		if ($this->dep('datos')->esta_cargada()) {
			$form->set_datos($this->dep('datos')->tabla('agentes')->get());
		} else {
			$this->pantalla()->eliminar_evento('eliminar');
		}
	}

	function evt__formulario__modificacion($datos)
	{
		$this->dep('datos')->tabla('agentes')->set($datos);
	}

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

	function ini()
	{
	$nombre = toba::usuario()->get_nombre();
	$user_id = toba::usuario()->get_id();
	$this->perfil = implode('/', toba::usuario()->get_perfiles_funcionales());
	$sql = "SELECT legajo FROM public.agentes WHERE email = '$user_id'";
	$rs = toba::db('desempenio')->consultar($sql);
	$this->legajo = $rs[0]['legajo'];
	$this->set_pantalla('pant_seleccion');
	}
}


?>