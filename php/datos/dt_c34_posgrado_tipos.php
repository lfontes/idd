<?php
class dt_c34_posgrado_tipos extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_posgrado FROM c34_posgrado_tipos ORDER BY tipo_posgrado";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>