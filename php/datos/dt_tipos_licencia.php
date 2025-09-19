<?php
class dt_tipos_licencia extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_licencia FROM tipos_licencia ORDER BY tipo_licencia";
		return toba::db('desempenio')->consultar($sql);
	}

	function get_listado()
	{
		$sql = "SELECT
			t_tl.id,
			t_tl.tipo_licencia
		FROM
			tipos_licencia as t_tl
		ORDER BY tipo_licencia";
		return toba::db('desempenio')->consultar($sql);
	}

}
?>