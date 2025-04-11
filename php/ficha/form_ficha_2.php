<?php
class form_ficha_2 extends pruebas_ei_formulario
{
    function extender_objeto_js()
    {
        echo "
            {$this->objeto_js}.evt__fecha_alta__validar = function()
            {
                var fechaAlta = this.ef('fecha_alta').fecha(); // Obtiene la fecha ingresada
				var fechaActual = new Date();
				fechaAlta.setHours(0, 0, 0, 0);
				fechaActual.setHours(0, 0, 0, 0);
				// Asegúrate de convertir fechaAlta a un objeto Date si no lo es ya
				if (fechaAlta > fechaActual) {
    				this.ef('fecha_alta').set_error('La fecha  debe ser menor o igual a la fecha actual.');
    				//alert('La fecha debe ser mayor o igual a la fecha actual. Fecha ingresada: ' + fechaAlta);
    				return false;
				}
				return true;
             }

             {$this->objeto_js}.evt__modificacion = function()
             {
                //alert('Validacion de fecha al guardar');
                var fechaActual = new Date();
				fechaActual.setHours(0, 0, 0, 0);
                this.ef('fecha_modif').set_fecha(fechaActual); // pone fecha actual en fecha de modificacion
                return true;
            
                }
		
        ";
    }
}
