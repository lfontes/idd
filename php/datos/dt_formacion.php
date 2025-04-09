<?php
class dt_formacion extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_formacion FROM formacion ORDER BY tipo_formacion";
		return toba::db('desempenio')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['tipo_formacion'])) {
			$where[] = "tipo_formacion ILIKE ".quote("%{$filtro['tipo_formacion']}%");
		}
		$sql = "SELECT
			t_f.id,
			t_f.tipo_formacion,
			t_f.valor
		FROM
			formacion as t_f
		ORDER BY tipo_formacion";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

}
?>