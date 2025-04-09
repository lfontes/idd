<?php
class dt_c35_tipos_presentacion extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_presentacion FROM c35_tipos_presentacion ORDER BY tipo_presentacion";
		return toba::db('desempenio')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['tipo_presentacion'])) {
			$where[] = "tipo_presentacion ILIKE ".quote("%{$filtro['tipo_presentacion']}%");
		}
		$sql = "SELECT
			t_ctp.id,
			t_ctp.tipo_presentacion,
			t_ctp.valor
		FROM
			c35_tipos_presentacion as t_ctp
		ORDER BY tipo_presentacion";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

}
?>