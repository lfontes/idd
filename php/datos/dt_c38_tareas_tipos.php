<?php
class dt_c38_tareas_tipos extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_tarea FROM c38_tareas_tipos ORDER BY tipo_tarea";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>