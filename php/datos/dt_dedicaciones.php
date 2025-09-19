<?php
class dt_dedicaciones extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, dedicacion FROM dedicaciones ORDER BY dedicacion";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>