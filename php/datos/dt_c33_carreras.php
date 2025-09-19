<?php
class dt_c33_carreras extends pruebas_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['carrera'])) {
			$where[] = "carrera ILIKE ".quote("%{$filtro['carrera']}%");
		}
		$sql = "SELECT
			t_cc.id,
			t_cc.carrera,
			t_cc.valor
		FROM
			c33_carreras as t_cc
		ORDER BY carrera";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

	function get_descripciones()
	{
		$sql = "SELECT id, carrera FROM c33_carreras ORDER BY carrera";
		return toba::db('desempenio')->consultar($sql);
	}

}
?>