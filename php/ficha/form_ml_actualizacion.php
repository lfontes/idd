<?php
class form_ml_actualizacion extends pruebas_ei_formulario_ml
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Validacion de EFs -----------------------------------
		
		{$this->objeto_js}.evt__f_hasta__validar = function(fila)
		{
				var fechaDesde = this.ef('f_desde').fecha(); // Obtiene la fecha ingresada
				var fechaHasta = this.ef('f_hasta').fecha();
				fechaDesde.setHours(0, 0, 0, 0);
				fechaHasta.setHours(0, 0, 0, 0);
				// Asegúrate de convertir fechaAlta a un objeto Date si no lo es ya
				if (fechaHasta < fechaDesde) {
    				//this.ef('f_desde').set_error('La fecha hasr debe ser mayor o igual a la fecha desde.');
    				alert('La fecha debe ser mayor o igual a la fecha de inicio. Fecha ingresada: ' + fechaDesde);
    				return false;
				}
				return true;
		}
		
		{$this->objeto_js}.evt__horas__validar = function(fila)
		{
			if (this.ef('horas').get_estado() == '0') {
				this.ef('horas').set_error('El campo horas no puede ser cero.');
				return false;
			}
			return true;
		}
		";
	}
}
