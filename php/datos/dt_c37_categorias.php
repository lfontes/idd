<?php
class dt_c37_categorias extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, categoria FROM c37_categorias ORDER BY categoria";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>