<?php
class dt_c38_tareas_tipos extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_tarea FROM c38_tareas_tipos ORDER BY tipo_tarea";
		return toba::db('desempenio')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['tipo_tarea'])) {
			$where[] = "tipo_tarea ILIKE ".quote("%{$filtro['tipo_tarea']}%");
		}
		$sql = "SELECT
			t_ctt.id,
			t_ctt.tipo_tarea,
			t_ctt.valor
		FROM
			c38_tareas_tipos as t_ctt
		ORDER BY tipo_tarea";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

}
?>