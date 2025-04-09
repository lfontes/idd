<?php
class dt_c35_tipos_participacion extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_participacion FROM c35_tipos_participacion ORDER BY tipo_participacion";
		return toba::db('desempenio')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['tipo_participacion'])) {
			$where[] = "tipo_participacion ILIKE ".quote("%{$filtro['tipo_participacion']}%");
		}
		$sql = "SELECT
			t_ctp.id,
			t_ctp.tipo_participacion,
			t_ctp.valor
		FROM
			c35_tipos_participacion as t_ctp
		ORDER BY tipo_participacion";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

}
?>