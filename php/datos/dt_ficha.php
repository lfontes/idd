<?php
class dt_ficha extends pruebas_datos_tabla
{
	function get_listado($filtro=array())
	{
		$where = array();
		if (isset($filtro['dni_doc'])) {
			$where[] = "dni_doc = ".quote($filtro['dni_doc']);
		}
		if (isset($filtro['legajo_doc'])) {
			$where[] = "legajo_doc = ".quote($filtro['legajo_doc']);
		}

		if (! in_array('admin', toba::usuario()->get_perfiles_funcionales())) {
			$where[] = "user_id = ".quote(toba::usuario()->get_id());
		}
		
		$sql = "SELECT
			t_f.id,
			t_f.dni_doc,
			t_f.legajo_doc,
			t_f.fecha_alta,
			t_f.fecha_modif,
			t_f.departamento_id,
			t_dep.departamento,
			t_f.catedra_id,
			t_cat.nombre_catedra,
			t_f.categoria_id,
			t_catg.categoria as categoria_doc,
			t_ded.dedicacion as dedicacion_doc,
			t_f.dedicacion_id,
			t_f.user_id,
			(COALESCE(idd.ad1,0))+(COALESCE(idd.ad2,0))+(COALESCE(idd.ad3,0)) as idd 
		FROM
			ficha as t_f
			LEFT OUTER JOIN departamentos as t_dep ON (t_f.departamento_id = t_dep.id_departamento)
			LEFT OUTER JOIN catedras as t_cat ON (t_f.catedra_id = t_cat.id_catedra)
			LEFT OUTER JOIN categorias_doc as t_catg ON (t_f.categoria_id = t_catg.id)
			left OUTER JOIN dedicaciones as t_ded ON (t_f.dedicacion_id = t_ded.id)
			LEFT OUTER JOIN vista_idd as idd ON (t_f.id = idd.ficha_id)
		ORDER BY categoria";
		if (count($where)>0) {
			$sql = sql_concatenar_where($sql, $where);
		}
		return toba::db('desempenio')->consultar($sql);
	}







}
?>