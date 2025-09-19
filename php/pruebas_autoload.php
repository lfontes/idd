<?php
/**
 * Esta clase fue y será generada automáticamente. NO EDITAR A MANO.
 * @ignore
 */
class pruebas_autoload 
{
	static function existe_clase($nombre)
	{
		return isset(self::$clases[$nombre]);
	}

	static function cargar($nombre)
	{
		if (self::existe_clase($nombre)) { 
			 require_once(dirname(__FILE__) .'/'. self::$clases[$nombre]); 
		}
	}

	static protected $clases = array(
		'pruebas_ci' => 'extension_toba/componentes/pruebas_ci.php',
		'pruebas_cn' => 'extension_toba/componentes/pruebas_cn.php',
		'pruebas_datos_relacion' => 'extension_toba/componentes/pruebas_datos_relacion.php',
		'pruebas_datos_tabla' => 'extension_toba/componentes/pruebas_datos_tabla.php',
		'pruebas_ei_arbol' => 'extension_toba/componentes/pruebas_ei_arbol.php',
		'pruebas_ei_archivos' => 'extension_toba/componentes/pruebas_ei_archivos.php',
		'pruebas_ei_calendario' => 'extension_toba/componentes/pruebas_ei_calendario.php',
		'pruebas_ei_codigo' => 'extension_toba/componentes/pruebas_ei_codigo.php',
		'pruebas_ei_cuadro' => 'extension_toba/componentes/pruebas_ei_cuadro.php',
		'pruebas_ei_esquema' => 'extension_toba/componentes/pruebas_ei_esquema.php',
		'pruebas_ei_filtro' => 'extension_toba/componentes/pruebas_ei_filtro.php',
		'pruebas_ei_firma' => 'extension_toba/componentes/pruebas_ei_firma.php',
		'pruebas_ei_formulario' => 'extension_toba/componentes/pruebas_ei_formulario.php',
		'pruebas_ei_formulario_ml' => 'extension_toba/componentes/pruebas_ei_formulario_ml.php',
		'pruebas_ei_grafico' => 'extension_toba/componentes/pruebas_ei_grafico.php',
		'pruebas_ei_mapa' => 'extension_toba/componentes/pruebas_ei_mapa.php',
		'pruebas_servicio_web' => 'extension_toba/componentes/pruebas_servicio_web.php',
		'pruebas_comando' => 'extension_toba/pruebas_comando.php',
		'pruebas_modelo' => 'extension_toba/pruebas_modelo.php',
	);
}
?>