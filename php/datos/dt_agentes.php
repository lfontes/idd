<?php
class dt_agentes extends pruebas_datos_tabla
{
	function get_listado($filtro=array())
	{	
		$where = array();
		if (isset($filtro['legajo'])) {
			$where[] = "legajo = ".quote($filtro['legajo']);
		}
		if (isset($filtro['dni'])) {
			$where[] = "dni = ".quote($filtro['dni']);
		}
		$sql = "SELECT
			t_a.legajo,
			t_a.ncargo,
			t_a.apellido,
			t_a.nombre,
			t_a.fec_nacim,
			t_a.dni,
			t_a.fecha_ingreso,
			t_a.estado_civil,
			t_a.caracter,
			t_a.categoria,
			t_a.agrupamiento,
			t_a.escalafon,
			t_a.cod_depcia,
			t_a.cuil,
			t_a.mayor_dedicacion,
			t_a.funcion_critica,
			t_a.tipo_sexo,
			t_a.email,
			t_a.telefono,
			t_a.cod_dedic,
			t_a.cant_horas,
			t_a.subrogancia
		FROM
			public.agentes as t_a
		ORDER BY nombre";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		
		return toba::db('desempenio')->consultar($sql);
	}


}
?>