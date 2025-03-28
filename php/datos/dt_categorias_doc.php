<?php
class dt_categorias_doc extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, categoria  FROM categorias_doc ORDER BY id";
		return toba::db('desempenio')->consultar($sql);
	}

}
?>