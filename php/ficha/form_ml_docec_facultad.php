<?php
class form_ml_docec_facultad extends pruebas_ei_formulario_ml
{
	 //-----------------------------------------------------------------------------------
	 //---- JAVASCRIPT -------------------------------------------------------------------
	 //-----------------------------------------------------------------------------------
     function extender_objeto_js()
     {
         // obtener año desde memoria (fallback al año actual)
         $anio_php = toba::memoria()->get_dato_instancia('anio');
         if (is_null($anio_php)) {
             $anio_php = date('Y');
         }
         $anio_js = json_encode($anio_php);

          echo "
          {$this->objeto_js}.evt__esp_curricular_id__procesar = function()
          {
              var espacio = this.controlador.dep('docec_facultad').ef('esp_curricular_id').get_estado();
              var anio = {$anio_js}; // año pasado desde PHP
             //alert('Espacio curricular seleccionado: ' + anio);
              this.controlador.ajax('get_inscriptos_por_espacio', [espacio,anio], this, this.actualizar_inscriptos);
          }
          {$this->objeto_js}.actualizar_inscriptos = function(datos)
         {
             this.ef('cant_inscriptos').set_estado(datos);
         }
         ";
     }

}
?>