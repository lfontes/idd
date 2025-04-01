<?php
class dt_c35_tipos_participacion extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_participacion FROM c35_tipos_participacion ORDER BY tipo_participacion";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>