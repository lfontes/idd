<?php>
class dependencia_esp_curriculares 
{
    static function get_espacios_curriculares($id_carrera)
    {
        $sql = "SELECT
			t_cect.id,
			t_cect.tipo_espacio_curricular,
			t_cect.valor
		FROM
			c33_espacios_curriculares_tipos as t_cect
        where t_cect.carrera_id = $id_carrera
		ORDER BY tipo_espacio_curricular";

return toba::db('desempenio')->consultar($sql);
    }
    
    
  
}

</php>
