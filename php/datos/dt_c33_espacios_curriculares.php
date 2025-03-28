<?php
class dt_c33_espacios_curriculares extends pruebas_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['espacio_curricular'])) {
			$where[] = "espacio_curricular ILIKE ".quote("%{$filtro['espacio_curricular']}%");
		}
		if (isset($filtro['carrera_id'])) {
			$where[] = "carrera_id = ".quote($filtro['carrera_id']);
		}
		if (isset($filtro['tipo_esp_curr'])) {
			$where[] = "tipo_esp_curr = ".quote($filtro['tipo_esp_curr']);
		}
		$sql = "SELECT
			t_cec.id,
			t_cec.espacio_curricular,
			t_cec.valor,
			t_cec.carrera_id,
			t_cec.tipo_esp_curr,
			t_esp.tipo_espacio_curricular,
			t_car.carrera
		FROM
			c33_espacios_curriculares as t_cec
			LEFT OUTER JOIN c33_carreras as t_car ON (t_cec.carrera_id = t_car.id)
			LEFT OUTER JOIN c33_espacios_curriculares_tipos as t_esp ON (t_cec.tipo_esp_curr = t_esp.id)
		ORDER BY espacio_curricular";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}


	function get_descripciones()
	{
		$sql = "SELECT id, espacio_curricular FROM c33_espacios_curriculares ORDER BY espacio_curricular";
		return toba::db('desempenio')->consultar($sql);
	}

}
?>