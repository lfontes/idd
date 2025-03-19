<?php
class dt_ficha extends pruebas_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_f.id,
			t_f.dni_doc,
			t_f.legajo_doc,
			t_f.fecha_alta,
			t_f.fecha_modif,
			t_f.departamento_id,
			t_f.catedra_id,
			t_f.categoria,
			t_f.dedicacion,
			t_f.user_id
		FROM
			ficha as t_f
			where t_f.user_id = ".quote(toba::usuario()->get_id())."
		ORDER BY categoria";
		return toba::db('desempenio')->consultar($sql);
	}





}
?>