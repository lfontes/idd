<?php
class dt_c34_participacion_tipos extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_participacion FROM c34_participacion_tipos ORDER BY tipo_participacion";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>