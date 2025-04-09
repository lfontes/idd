<?php
class dt_c38_materiales_tipos extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_material FROM c38_materiales_tipos ORDER BY tipo_material";
		return toba::db('desempenio')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['tipo_material'])) {
			$where[] = "tipo_material ILIKE ".quote("%{$filtro['tipo_material']}%");
		}
		$sql = "SELECT
			t_cmt.id,
			t_cmt.tipo_material,
			t_cmt.valor
		FROM
			c38_materiales_tipos as t_cmt
		ORDER BY tipo_material";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

}
?>