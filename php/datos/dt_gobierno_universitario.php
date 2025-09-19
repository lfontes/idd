<?php
class dt_gobierno_universitario extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, estamento FROM gobierno_universitario ORDER BY estamento";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>