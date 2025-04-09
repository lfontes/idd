<?php
class dt_c37_categorias extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, categoria FROM c37_categorias ORDER BY categoria";
		return toba::db('desempenio')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['categoria'])) {
			$where[] = "categoria ILIKE ".quote("%{$filtro['categoria']}%");
		}
		$sql = "SELECT
			t_cc.id,
			t_cc.categoria,
			t_cc.valor
		FROM
			c37_categorias as t_cc
		ORDER BY categoria";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

}
?>