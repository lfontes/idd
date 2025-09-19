<?php
class dt_departamentos extends pruebas_datos_tabla
{
	function get_listado()
	{
		$sql = "SELECT
			t_d.id_departamento,
			t_d.departamento
		FROM
			departamentos as t_d
		ORDER BY departamento";
		return toba::db('desempenio')->consultar($sql);
	}

		function get_descripciones()
		{
			$sql = "SELECT id_departamento, departamento FROM departamentos ORDER BY departamento";
			return toba::db('desempenio')->consultar($sql);
		}



	

}
?>