<?php
class dt_c34_posgrado_tipos extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_posgrado FROM c34_posgrado_tipos ORDER BY tipo_posgrado";
		return toba::db('desempenio')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['tipo_posgrado'])) {
			$where[] = "tipo_posgrado ILIKE ".quote("%{$filtro['tipo_posgrado']}%");
		}
		$sql = "SELECT
			t_cpt.id,
			t_cpt.tipo_posgrado,
			t_cpt.valor
		FROM
			c34_posgrado_tipos as t_cpt
		ORDER BY tipo_posgrado";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

}
?>