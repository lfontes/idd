<?php
class dt_c36_tipos_participacion extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_participacionn FROM c36_tipos_participacion ORDER BY tipo_participacionn";
		return toba::db('desempenio')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['tipo_participacionn'])) {
			$where[] = "tipo_participacionn ILIKE ".quote("%{$filtro['tipo_participacionn']}%");
		}
		$sql = "SELECT
			t_ctp.id,
			t_ctp.tipo_participacionn,
			t_ctp.valor
		FROM
			c36_tipos_participacion as t_ctp
		ORDER BY tipo_participacionn";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

}
?>