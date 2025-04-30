<?php
class dt_ficha extends pruebas_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['id'])) {
			$where[] = "id = ".quote($filtro['id']);
		}
		if (isset($filtro['legajo_doc'])) {
			$where[] = "legajo_doc = ".quote($filtro['legajo_doc']);
		}
		$sql = "SELECT
			t_f.id,
			t_f.dni_doc,
			t_f.legajo_doc,
			t_f.fecha_alta,
			t_f.fecha_modif,
			t_f.departamento_id,
			t_f.catedra_id,
			t_f.user_id,
			t_f.categoria_id,
			t_f.dedicacion_id
		FROM
			ficha as t_f
		ORDER BY user_id";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}









}
?>