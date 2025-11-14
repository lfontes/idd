<?php
class form_ml_patentes extends pruebas_ei_formulario_ml
{
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
		echo "
		//---- Validacion de EFs -----------------------------------
		
		{$this->objeto_js}.evt__fecha_aprob__validar = function(fila)
		{
				var fechaDesde = this.ef('fecha_pres').fecha(); // Obtiene la fecha ingresada
				var fechaHasta = this.ef('fecha_aprob').fecha();
				fechaDesde.setHours(0, 0, 0, 0);
				fechaHasta.setHours(0, 0, 0, 0);
				// AsegÃºrate de convertir fechaAlta a un objeto Date si no lo es ya
				if (fechaHasta < fechaDesde) {
    				//this.ef('f_desde').set_error('La fecha aprobación debe ser mayor o igual a la fecha de presentación.');
    				alert('La fecha debe ser mayor o igual a la fecha de presentacion. Fecha ingresada: ' + fechaDesde);
    				return false;
				}
				return true;
		}
		
		
		";
	}
}