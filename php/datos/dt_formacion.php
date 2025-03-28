<?php
class dt_formacion extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_formacion FROM formacion ORDER BY tipo_formacion";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>