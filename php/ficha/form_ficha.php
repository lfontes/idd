<?php
class form_ficha extends pruebas_ei_formulario
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Procesamiento de EFs --------------------------------
		
		{$this->objeto_js}.evt__fecha_alta__procesar = function(es_inicial)
		{
			// alert('Entrando en la función de procesamiento de fecha'); // Alerta para
				}
		
		//---- Validacion de EFs -----------------------------------
		
		{$this->objeto_js}.evt__fecha_alta__validar = function()
		{
			// // alert('Entrando en la función de validación de fecha'); // Alerta para confirmar la ejecución
			 var fechaAlta = this.ef('fecha_alta').fecha(); // Obtiene la fecha ingresada
			 var fechaActual = new Date();
			
			 if (fechaAlta < fechaActual) {
			 	this.ef('fecha_alta').set_error('La fecha debe ser mayor o igual a la fecha actual.');
			 	//alert('La fecha debe ser mayor o igual a la fecha actual.');
			 	return false;
			 }
			 return true;
		}
		";
	}

}

?>