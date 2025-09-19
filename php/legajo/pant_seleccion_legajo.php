<?php
class pant_seleccion_legajo extends toba_ei_pantalla
{
    function extender_objeto_js()
    {
        $perfil_funcional = implode('/', toba::usuario()->get_perfiles_funcionales());
        ei_arbol($perfil_funcional);
        if ($perfil_funcional == 'docente') {
            echo "
                // Código JavaScript para evitar la carga del filtro de datos
                this.ef('filtro').ocultar();
            ";
        }
    }
}
?>