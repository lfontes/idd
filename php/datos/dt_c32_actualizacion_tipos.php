<?php
class dt_c32_actualizacion_tipos extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_actualizacion FROM c32_actualizacion_tipos ORDER BY tipo_actualizacion";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>