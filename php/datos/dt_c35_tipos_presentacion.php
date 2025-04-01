<?php
class dt_c35_tipos_presentacion extends pruebas_datos_tabla
{
	function get_descripciones()
	{
		$sql = "SELECT id, tipo_presentacion FROM c35_tipos_presentacion ORDER BY tipo_presentacion";
		return toba::db('desempenio')->consultar($sql);
	}

}

?>