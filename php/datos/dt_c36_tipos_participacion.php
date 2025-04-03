<?php
class dt_c36_tipos_participacion extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_participacionn FROM c36_tipos_participacion ORDER BY tipo_participacionn";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>