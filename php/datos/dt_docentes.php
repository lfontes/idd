<?php
class dt_docentes extends pruebas_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_d.id,
			t_d.ayn,
			t_d.dni,
			t_d.legajo,
			t_d.email,
			t_d.domicilio,
			t_d.catedra_id,
			t_d.departamento_id
		FROM
			docentes as t_d
		ORDER BY ayn";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>