<?php
class dt_c38_materiales_tipos extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_material FROM c38_materiales_tipos ORDER BY tipo_material";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>