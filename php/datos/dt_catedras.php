<?php
class dt_catedras extends pruebas_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['id_departamento'])) {
			$where[] = "id_departamento = ".quote($filtro['id_departamento']);
		}
		$sql = "SELECT
			t_c.id_catedra,
			t_c.nombre_catedra,
			t_d.departamento as id_departamento_nombre
		FROM
			catedras as t_c	LEFT OUTER JOIN departamentos as t_d ON (t_c.id_departamento = t_d.id_departamento)
		ORDER BY nombre_catedra";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}

}

?>