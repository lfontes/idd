<?php
class dt_c34_participacion_tipos extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_participacion FROM c34_participacion_tipos ORDER BY tipo_participacion";
		return toba::db('desempenio')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['tipo_participacion'])) {
			$where[] = "tipo_participacion ILIKE ".quote("%{$filtro['tipo_participacion']}%");
		}
		$sql = "SELECT
			t_cpt.id,
			t_cpt.tipo_participacion
		FROM
			c34_participacion_tipos as t_cpt
		ORDER BY tipo_participacion";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

}
?>