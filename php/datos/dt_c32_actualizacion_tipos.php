<?php
class dt_c32_actualizacion_tipos extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_actualizacion FROM c32_actualizacion_tipos ORDER BY tipo_actualizacion";
		return toba::db('desempenio')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['tipo_actualizacion'])) {
			$where[] = "tipo_actualizacion ILIKE ".quote("%{$filtro['tipo_actualizacion']}%");
		}
		$sql = "SELECT
			t_cat.id,
			t_cat.tipo_actualizacion,
			t_cat.valor
		FROM
			c32_actualizacion_tipos as t_cat
		ORDER BY tipo_actualizacion";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

}
?>