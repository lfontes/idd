<?php
class dt_c33_espacios_curriculares_tipos extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_espacio_curricular FROM c33_espacios_curriculares_tipos ORDER BY tipo_espacio_curricular";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>