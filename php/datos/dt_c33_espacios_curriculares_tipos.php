<?php
class dt_c33_espacios_curriculares_tipos extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_espacio_curricular FROM c33_espacios_curriculares_tipos ORDER BY tipo_espacio_curricular";
		return toba::db('desempenio')->consultar($sql);
	}

	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['tipo_espacio_curricular'])) {
			$where[] = "tipo_espacio_curricular ILIKE ".quote("%{$filtro['tipo_espacio_curricular']}%");
		}
		$sql = "SELECT
			t_cect.id,
			t_cect.tipo_espacio_curricular,
			t_cect.valor
		FROM
			c33_espacios_curriculares_tipos as t_cect
		ORDER BY tipo_espacio_curricular";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

}
?>