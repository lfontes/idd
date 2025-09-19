<?php
class form_ml_docec_facultad extends pruebas_ei_formulario_ml
{
	 //-----------------------------------------------------------------------------------
	 //---- JAVASCRIPT -------------------------------------------------------------------
	 //-----------------------------------------------------------------------------------
     function extender_objeto_js()
     {
          echo "
          {$this->objeto_js}.evt__esp_curricular_id__procesar = function()
          {
              var espacio = this.ef('esp_curricular_id').get_estado();
             //alert('Espacio curricular seleccionado: ' + espacio);
              this.controlador.ajax('get_inscriptos_por_espacio', [espacio], this, this.actualizar_inscriptos);
          }
          {$this->objeto_js}.actualizar_inscriptos = function(datos)
         {
             this.ef('cant_inscriptos').set_estado(datos);
         }
         ";
     }

}
?>