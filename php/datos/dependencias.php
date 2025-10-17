<?php


class dependencias
{
	static function get_espacios_curriculares($carrera)
	{
		$sql = "SELECT
			t_cec.cod_guarani,
			t_cec.espacio_curricular
			FROM
			c33_espacios_curriculares as t_cec
            LEFT OUTER JOIN c33_carreras as t_car ON (t_cec.carrera_id = t_car.id)
            WHERE t_cec.carrera_id = $carrera			
		ORDER BY espacio_curricular";

        return toba::db('desempenio')->consultar($sql);

	}
    static function get_tipo_espacio_curricular($espacio_curricular)
    {
        $sql = "SELECT t_cec.tipo_esp_curr as id,t_esp_tipos.tipo_espacio_curricular as tipo_espacio_curricular
                 FROM c33_espacios_curriculares as t_cec                
                left outer join c33_espacios_curriculares_tipos as t_esp_tipos on (t_cec.tipo_esp_curr = t_esp_tipos.id)
                where t_cec.cod_guarani =$espacio_curricular
                ORDER BY tipo_espacio_curricular";
        return toba::db('desempenio')->consultar($sql);
    }
    static function get_estamento_cargo($estamento)
    {
        $sql = "SELECT cargo as cargo, cargo as cargo_id FROM gobierno_universitario
                        where estamento = '$estamento'
                        order by cargo";
        return toba::db('desempenio')->consultar($sql);
    }

    static function get_catedras($departamento)
    {
        $sql = "SELECT id_catedra as id, nombre_catedra as catedra FROM catedras
                        where id_departamento = $departamento
                        order by catedra";
        return toba::db('desempenio')->consultar($sql);
    }
	

   
}

?>