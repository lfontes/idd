<?php

// El autoload de Toba (toba_nucleo::cargar_autoload_composer()) carga el
// vendor/autoload.php del framework, no el de este proyecto: sin este
// require, Pruebas\Indice\* no resuelve dentro de una app Toba en marcha.
require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Único lugar del módulo Índice que usa toba::db() (.claude/rules/indice.md).
 *
 * Tres responsabilidades:
 *  - items_*(): traduce las tablas del Informe de Labor a
 *    Pruebas\Indice\Dto\ItemDeclarado, según docs/indice/mapeo-origen.md.
 *    Sólo implementa lo que ese documento no marca como PENDIENTE.
 *  - componentes()/umbral(): hidratan la config de indice.componente,
 *    indice.valoracion e indice.umbral (sql/indice/01_indice_ddl.sql) a
 *    Pruebas\Indice\Dto\Componente. No hay reglas de negocio por dimensión
 *    acá: el modelo de componentes ya es genérico, así que sirve para
 *    cualquier dimensión sembrada (AD1..AD8, y las que se agreguen), sin
 *    violar la regla de "una dimensión a la vez" del mapeo de orígenes.
 *  - guardarEvaluacionDimension(): persiste el resultado de una dimensión en
 *    un año puntual (indice.evaluacion/evaluacion_dimension/evaluacion_item).
 *  - guardarEvaluacionPeriodo(): persiste el cierre de período (evaluacion_periodo,
 *    fc/fck por año) que arma indice_orquestador::cerrarPeriodo(). Todavía no
 *    arma evaluacion_actividad/evaluacion_indice — esos necesitan resolver la
 *    consolidación entre actividades (docs/indice/decisiones-pendientes.md, A1).
 *    evaluacion.docente_id es legajo_doc de public.ficha
 *    (sql/indice/06_evaluacion_docente_id.sql): no hay tabla local de
 *    "docente" con id propio.
 */
class indice_repositorio
{
    /**
     * public.categorias_doc.id -> código de indice.categoria_docente.
     * Se mapea por id, no por el texto de la columna 'categoria': evita
     * arrastrar el problema de encoding LATIN1/UTF8 de la fuente 'desempenio'
     * (proyecto.ini) y es estable aunque cambie la redacción del nombre.
     */
    private const CATEGORIAS = [
        1 => 'T',   // Titular
        2 => 'AS',  // Asociado
        3 => 'AJ',  // Adjunto
        4 => 'JTP', // JTP
        5 => 'AY1', // Ayudante 1ra
        6 => 'AY2', // Ayudante 2da
    ];

    /**
     * public.dedicaciones.id -> código de indice.dedicacion. 'Full' (id 4)
     * no tiene equivalente propio en la normativa del índice (EX/SE/DS):
     * por decisión confirmada por el usuario (no en la planilla origen), se
     * trata como Exclusiva. Si en el futuro aparece una normativa que la
     * distinga, esto deja de alcanzar.
     */
    private const DEDICACIONES = [
        1 => 'EX', // Exclusiva
        2 => 'SE', // Semi Exclusiva
        3 => 'DS', // Simple
        4 => 'EX', // Full -> tratada como Exclusiva
    ];

    /**
     * public.c32_actualizacion_tipos.id -> bandera que emite AD2 (docs/indice/mapeo-origen.md).
     * Resuelto por id, no por texto (mismo motivo que CATEGORIAS/DEDICACIONES).
     * Seminario (6) se asimila a Curso: decisión confirmada por el usuario,
     * no es normativa escrita. Pasantía (4) y Distinción (5) fueron agregadas
     * al catálogo por el usuario durante esta sesión (2026-08-28): antes no
     * existían filas para esos tipos.
     */
    private const TIPO_ACT_CURSO = 1;
    private const TIPO_ACT_TALLER = 2;
    private const TIPO_ACT_FORM_OBLIGATORIA = 3;
    private const TIPO_ACT_PASANTIA = 4;
    private const TIPO_ACT_DISTINCION = 5;
    private const TIPO_ACT_SEMINARIO = 6; // se asimila a Curso

    /**
     * public.c34_participacion_tipos.id -> porcentaje de participación que
     * usa el término 'proporcion' de AD4. No hay ninguna tabla real con este
     * porcentaje (docs/indice/mapeo-origen.md, AD4): confirmado directamente
     * por el usuario (sesión 2026-08-28), no es normativa escrita. Un
     * participacion_id fuera de este catálogo (11 filas reales con valores
     * sueltos como 28, 40, 63... que parecen ser el default de la secuencia
     * de la columna, nunca un rol real cargado) no lanza excepción: cuenta
     * 0%, también confirmado por el usuario — criterio distinto al resto de
     * los lookups (AD1/AD3), que sí explotan ante una clave desconocida.
     */
    private const PARTICIPACION_PORCENTAJE = [
        1 => '100', // Coordinador
        2 => '80',  // Docente
        3 => '20',  // Ayudante
    ];

    /**
     * AD1 — Formación académica (docs/indice/mapeo-origen.md, sección AD1).
     * Una fila de public.formacion_academica = un ítem. La cantidad no se
     * emite: dos maestrías son dos ítems, no un ítem con cantidad=2
     * (decisión 1 del mapeo). tipo_formacion se emite como string porque el
     * cuadro de valoración lo indexa por el id numérico de public.formacion,
     * nunca por el nombre (decisión 5): el nombre sólo viaja como descripción.
     *
     * @return \Pruebas\Indice\Dto\ItemDeclarado[]
     */
    static function items_formacion_academica(int $legajo, int $anio): array
    {
        $ficha_id = self::ficha_id($legajo, $anio);
        if ($ficha_id === null) {
            return [];
        }

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT id, tipo_formacion, titulo, institucion, normativa, anio
               FROM public.formacion_academica
              WHERE ficha_id = :ficha_id
              ORDER BY id',
            ['ficha_id' => $ficha_id],
        );

        $items = [];
        foreach ($filas as $fila) {
            $items[] = new \Pruebas\Indice\Dto\ItemDeclarado(
                campos: ['tipo_formacion' => (string) $fila['tipo_formacion']],
                descripcion: self::asegurar_utf8($fila['titulo']),
                origenTabla: 'formacion_academica',
                origenId: (int) $fila['id'],
                detalle: [
                    'institucion' => self::asegurar_utf8($fila['institucion']),
                    'anio' => $fila['anio'] !== null ? (int) $fila['anio'] : null,
                    'normativa' => self::asegurar_utf8($fila['normativa']),
                ],
            );
        }

        return $items;
    }

    /**
     * AD2 — Actualización y capacitación en docencia (docs/indice/mapeo-origen.md,
     * sección AD2). Una fila de public.c32_actualizacion = un ítem.
     * certificado resuelve aprobado/asistencia (0=Asistencia, 1=Aprobación,
     * confirmado por el usuario); tipo_act resuelve curso/taller/
     * form_obligatoria/pasantia/distincion vía TIPO_ACT_*.
     *
     * @return \Pruebas\Indice\Dto\ItemDeclarado[]
     */
    static function items_capacitacion_docente(int $legajo, int $anio): array
    {
        $ficha_id = self::ficha_id($legajo, $anio);
        if ($ficha_id === null) {
            return [];
        }

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT id, tipo_act, nombre, institucion, f_desde, f_hasta, certificado, horas
               FROM public.c32_actualizacion
              WHERE ficha_id = :ficha_id
              ORDER BY id',
            ['ficha_id' => $ficha_id],
        );

        $items = [];
        foreach ($filas as $fila) {
            $tipo_act = (int) $fila['tipo_act'];
            $aprobado = (int) $fila['certificado'] === 1;

            $items[] = new \Pruebas\Indice\Dto\ItemDeclarado(
                campos: [
                    'horas' => (string) $fila['horas'],
                    'curso' => in_array($tipo_act, [self::TIPO_ACT_CURSO, self::TIPO_ACT_SEMINARIO], true) ? '1' : '0',
                    'taller' => $tipo_act === self::TIPO_ACT_TALLER ? '1' : '0',
                    'form_obligatoria' => $tipo_act === self::TIPO_ACT_FORM_OBLIGATORIA ? '1' : '0',
                    'pasantia' => $tipo_act === self::TIPO_ACT_PASANTIA ? '1' : '0',
                    'distincion' => $tipo_act === self::TIPO_ACT_DISTINCION ? '1' : '0',
                    'aprobado' => $aprobado ? '1' : '0',
                    'asistencia' => $aprobado ? '0' : '1',
                ],
                descripcion: self::asegurar_utf8($fila['nombre']),
                origenTabla: 'c32_actualizacion',
                origenId: (int) $fila['id'],
                detalle: [
                    'institucion' => self::asegurar_utf8($fila['institucion']),
                    'f_desde' => $fila['f_desde'],
                    'f_hasta' => $fila['f_hasta'],
                    'tipo_act' => $tipo_act,
                ],
            );
        }

        return $items;
    }

    /**
     * AD3 — Docencia en carreras de pregrado y grado (docs/indice/mapeo-origen.md,
     * sección AD3). Una fila de public.c33_docec_facultad = un ítem: un
     * espacio curricular declarado por el docente en la ficha del año.
     * tipo_esp_curricular_id resuelve 'tipo_espacio' por id (mismo catálogo
     * que indice.valoracion tabla_id 2: 1 Teórica, 2 Teórico-Práctica,
     * 3 Talleres y Laboratorios, 4 Prácticas Supervisadas). A diferencia de
     * AD1/AD2, acá no hubo que preguntar nada: la tabla ya trae carga_horario
     * y cant_inscriptos como snapshot propio (no hace falta consultar
     * Guaraní en el momento del cálculo). 'participacion' se guarda en el
     * detalle para trazabilidad, pero el componente sembrado no la usa.
     * carga_horario/cant_inscriptos pueden venir NULL en datos reales (39 y 2
     * filas respectivamente al momento de este mapeo): el motor ya trata un
     * campo ausente como 0 (Componente tipo 'proporcion'), no hace falta
     * lógica especial acá.
     *
     * @return \Pruebas\Indice\Dto\ItemDeclarado[]
     */
    static function items_espacios_curriculares(int $legajo, int $anio): array
    {
        $ficha_id = self::ficha_id($legajo, $anio);
        if ($ficha_id === null) {
            return [];
        }

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT df.id, df.carga_horario, df.cant_inscriptos, df.tipo_esp_curricular_id,
                    df.participacion, ec.espacio_curricular, ca.carrera
               FROM public.c33_docec_facultad df
               LEFT OUTER JOIN public.c33_espacios_curriculares ec ON ec.id = df.esp_curricular_id
               LEFT OUTER JOIN public.c33_carreras ca ON ca.id = df.carrera_id
              WHERE df.ficha_id = :ficha_id
              ORDER BY df.id',
            ['ficha_id' => $ficha_id],
        );

        $items = [];
        foreach ($filas as $fila) {
            $items[] = new \Pruebas\Indice\Dto\ItemDeclarado(
                campos: [
                    'carga_horaria' => $fila['carga_horario'] !== null ? (string) $fila['carga_horario'] : null,
                    'estudiantes_inscriptos' => $fila['cant_inscriptos'] !== null ? (string) $fila['cant_inscriptos'] : null,
                    'tipo_espacio' => (string) $fila['tipo_esp_curricular_id'],
                ],
                descripcion: self::asegurar_utf8($fila['espacio_curricular']),
                origenTabla: 'c33_docec_facultad',
                origenId: (int) $fila['id'],
                detalle: [
                    'carrera' => self::asegurar_utf8($fila['carrera']),
                    'participacion' => $fila['participacion'] !== null ? (string) $fila['participacion'] : null,
                ],
            );
        }

        return $items;
    }

    /**
     * AD4 — Docencia de posgrado (docs/indice/mapeo-origen.md, sección AD4).
     * Una fila de public.c34_docec_posgrado = un ítem. tipo_posgrado_id
     * resuelve 'tipo_posgrado' por id (sql/indice/09_ad4_fix_tipo_posgrado.sql:
     * el componente sembrado originalmente decía 'carrera' pero estaba mal
     * etiquetado — es tipo de posgrado, no carrera). participacion_id resuelve
     * un % vía PARTICIPACION_PORCENTAJE; fuera de catálogo cuenta 0%, no
     * lanza excepción (confirmado por el usuario, ver esa constante).
     *
     * @return \Pruebas\Indice\Dto\ItemDeclarado[]
     */
    static function items_docencia_posgrado(int $legajo, int $anio): array
    {
        $ficha_id = self::ficha_id($legajo, $anio);
        if ($ficha_id === null) {
            return [];
        }

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT id, tipo_posgrado_id, participacion_id, creditos, caracter, institucion, denominacion, nombre
               FROM public.c34_docec_posgrado
              WHERE ficha_id = :ficha_id
              ORDER BY id',
            ['ficha_id' => $ficha_id],
        );

        $items = [];
        foreach ($filas as $fila) {
            $participacion_id = $fila['participacion_id'] !== null ? (int) $fila['participacion_id'] : null;

            $items[] = new \Pruebas\Indice\Dto\ItemDeclarado(
                campos: [
                    'creditos' => $fila['creditos'] !== null ? (string) $fila['creditos'] : null,
                    'participacion' => self::PARTICIPACION_PORCENTAJE[$participacion_id] ?? '0',
                    'tipo_posgrado' => (string) $fila['tipo_posgrado_id'],
                ],
                descripcion: self::asegurar_utf8($fila['denominacion']),
                origenTabla: 'c34_docec_posgrado',
                origenId: (int) $fila['id'],
                detalle: [
                    'nombre' => self::asegurar_utf8($fila['nombre']),
                    'institucion' => self::asegurar_utf8($fila['institucion']),
                    'caracter' => $fila['caracter'],
                ],
            );
        }

        return $items;
    }

    /**
     * AD5 — Participación en reuniones científicas de docencia
     * (docs/indice/mapeo-origen.md, sección AD5). Una fila de
     * public.c35_reu_cientificas = un ítem. tipo_participacion_id y
     * present_tipo_id resuelven por id (sql/indice/10_ad5_fix_valoracion_por_id.sql:
     * mismo bug que AD4, la tabla de valoración estaba keyed por texto).
     * referato se emite '1'/'0' para la clave_2 de doble entrada del cuadro
     * de tipo_presentacion.
     *
     * @return \Pruebas\Indice\Dto\ItemDeclarado[]
     */
    static function items_reuniones_docencia(int $legajo, int $anio): array
    {
        $ficha_id = self::ficha_id($legajo, $anio);
        if ($ficha_id === null) {
            return [];
        }

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT id, tipo_participacion_id, present_tipo_id, referato, organizador, lugar, fecha, titulo, autores
               FROM public.c35_reu_cientificas
              WHERE ficha_id = :ficha_id
              ORDER BY id',
            ['ficha_id' => $ficha_id],
        );

        $items = [];
        foreach ($filas as $fila) {
            $items[] = new \Pruebas\Indice\Dto\ItemDeclarado(
                campos: [
                    'tipo_participacion' => $fila['tipo_participacion_id'] !== null ? (string) $fila['tipo_participacion_id'] : null,
                    'tipo_presentacion' => $fila['present_tipo_id'] !== null ? (string) $fila['present_tipo_id'] : null,
                    'referato' => (bool) $fila['referato'] ? '1' : '0',
                ],
                descripcion: self::asegurar_utf8($fila['titulo']),
                origenTabla: 'c35_reu_cientificas',
                origenId: (int) $fila['id'],
                detalle: [
                    'organizador' => self::asegurar_utf8($fila['organizador']),
                    'lugar' => self::asegurar_utf8($fila['lugar']),
                    'fecha' => $fila['fecha'],
                    'autores' => self::asegurar_utf8($fila['autores']),
                ],
            );
        }

        return $items;
    }

    /**
     * AD6 — Proyectos en docencia acreditados (docs/indice/mapeo-origen.md,
     * sección AD6). Una fila de public.c36_proyectos_educativos = un ítem.
     * tipo_participacion_id resuelve por id (sql/indice/11_ad678_fix_valoracion_por_id.sql:
     * mismo bug de texto-vs-id que AD4/AD5).
     *
     * @return \Pruebas\Indice\Dto\ItemDeclarado[]
     */
    static function items_proyectos_docencia(int $legajo, int $anio): array
    {
        $ficha_id = self::ficha_id($legajo, $anio);
        if ($ficha_id === null) {
            return [];
        }

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT id, tipo_participacion_id, nombre_proyecto, normativa_acred, lugar_ejecucion, fecha_inicio, fecha_fin
               FROM public.c36_proyectos_educativos
              WHERE ficha_id = :ficha_id
              ORDER BY id',
            ['ficha_id' => $ficha_id],
        );

        $items = [];
        foreach ($filas as $fila) {
            $items[] = new \Pruebas\Indice\Dto\ItemDeclarado(
                campos: [
                    'tipo_participacion' => $fila['tipo_participacion_id'] !== null ? (string) $fila['tipo_participacion_id'] : null,
                ],
                descripcion: self::asegurar_utf8($fila['nombre_proyecto']),
                origenTabla: 'c36_proyectos_educativos',
                origenId: (int) $fila['id'],
                detalle: [
                    'normativa_acred' => self::asegurar_utf8($fila['normativa_acred']),
                    'lugar_ejecucion' => self::asegurar_utf8($fila['lugar_ejecucion']),
                    'fecha_inicio' => $fila['fecha_inicio'],
                    'fecha_fin' => $fila['fecha_fin'],
                ],
            );
        }

        return $items;
    }

    /**
     * AD7 — Formación de recursos humanos (docs/indice/mapeo-origen.md,
     * sección AD7). Una fila de public.c37_formacion_docec = un ítem.
     * categoria_id resuelve 'categoria_rrhh' por id (sql/indice/11_ad678_fix_valoracion_por_id.sql,
     * que agregó 'Tesista de grado' — faltaba en la siembra original).
     *
     * @return \Pruebas\Indice\Dto\ItemDeclarado[]
     */
    static function items_formacion_rrhh(int $legajo, int $anio): array
    {
        $ficha_id = self::ficha_id($legajo, $anio);
        if ($ficha_id === null) {
            return [];
        }

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT id, categoria_id, ayn, descripcion, rol
               FROM public.c37_formacion_docec
              WHERE ficha_id = :ficha_id
              ORDER BY id',
            ['ficha_id' => $ficha_id],
        );

        $items = [];
        foreach ($filas as $fila) {
            $items[] = new \Pruebas\Indice\Dto\ItemDeclarado(
                campos: [
                    'categoria_rrhh' => $fila['categoria_id'] !== null ? (string) $fila['categoria_id'] : null,
                ],
                descripcion: self::asegurar_utf8($fila['descripcion']),
                origenTabla: 'c37_formacion_docec',
                origenId: (int) $fila['id'],
                detalle: [
                    'ayn' => self::asegurar_utf8($fila['ayn']),
                    'rol' => $fila['rol'],
                ],
            );
        }

        return $items;
    }

    /**
     * AD8 — Producción de materiales pedagógicos (docs/indice/mapeo-origen.md,
     * sección AD8). Una fila de public.c38_materiales_pedagogicos = un ítem.
     * mat_tipo_id/tipo_tarea_id resuelven 'material'/'tarea' por id
     * (sql/indice/11_ad678_fix_valoracion_por_id.sql, que agregó 'Desarrollo
     * de apps' y 'Publicaciones' — faltaban en la siembra original).
     *
     * @return \Pruebas\Indice\Dto\ItemDeclarado[]
     */
    static function items_materiales_pedagogicos(int $legajo, int $anio): array
    {
        $ficha_id = self::ficha_id($legajo, $anio);
        if ($ficha_id === null) {
            return [];
        }

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT mp.id, mp.mat_tipo_id, mp.tipo_tarea_id, ec.espacio_curricular, ca.carrera
               FROM public.c38_materiales_pedagogicos mp
               LEFT OUTER JOIN public.c33_espacios_curriculares ec ON ec.id = mp.esp_curricular_id
               LEFT OUTER JOIN public.c33_carreras ca ON ca.id = mp.carrera_id
              WHERE mp.ficha_id = :ficha_id
              ORDER BY mp.id',
            ['ficha_id' => $ficha_id],
        );

        $items = [];
        foreach ($filas as $fila) {
            $items[] = new \Pruebas\Indice\Dto\ItemDeclarado(
                campos: [
                    'material' => $fila['mat_tipo_id'] !== null ? (string) $fila['mat_tipo_id'] : null,
                    'tarea' => $fila['tipo_tarea_id'] !== null ? (string) $fila['tipo_tarea_id'] : null,
                ],
                descripcion: self::asegurar_utf8($fila['espacio_curricular']),
                origenTabla: 'c38_materiales_pedagogicos',
                origenId: (int) $fila['id'],
                detalle: [
                    'carrera' => self::asegurar_utf8($fila['carrera']),
                ],
            );
        }

        return $items;
    }

    /**
     * Despacha al proveedor de ítems de una dimensión, por el nombre que
     * guarda indice.dimension.proveedor. Único punto de la clase que conoce
     * la lista completa de proveedores implementados: agregar una dimensión
     * es agregar un caso acá, no tocar indice_orquestador.
     *
     * @return \Pruebas\Indice\Dto\ItemDeclarado[]
     */
    static function items(string $proveedor, int $legajo, int $anio): array
    {
        return match ($proveedor) {
            'formacion_academica' => self::items_formacion_academica($legajo, $anio),
            'capacitacion_docente' => self::items_capacitacion_docente($legajo, $anio),
            'espacios_curriculares' => self::items_espacios_curriculares($legajo, $anio),
            'docencia_posgrado' => self::items_docencia_posgrado($legajo, $anio),
            'reuniones_docencia' => self::items_reuniones_docencia($legajo, $anio),
            'proyectos_docencia' => self::items_proyectos_docencia($legajo, $anio),
            'formacion_rrhh' => self::items_formacion_rrhh($legajo, $anio),
            'materiales_pedagogicos' => self::items_materiales_pedagogicos($legajo, $anio),
            default => throw new RuntimeException("Proveedor de ítems '{$proveedor}' no implementado."),
        };
    }

    /**
     * Nombre del proveedor de ítems de una dimensión (indice.dimension.proveedor),
     * para que indice_orquestador pueda despachar sin conocer el catálogo de
     * dimensiones de antemano.
     */
    static function proveedor(string $codigo_dimension, int $anio): string
    {
        $normativa_id = self::normativa_id($anio);

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT dim.proveedor
               FROM indice.dimension dim
               JOIN indice.actividad a ON a.id = dim.actividad_id
              WHERE dim.codigo = :codigo_dimension AND a.normativa_id = :normativa_id',
            ['codigo_dimension' => $codigo_dimension, 'normativa_id' => $normativa_id],
        );

        if ($filas === []) {
            throw new RuntimeException(
                "La dimensión '{$codigo_dimension}' no existe en la normativa vigente en {$anio}.",
            );
        }

        return (string) $filas[0]['proveedor'];
    }

    /**
     * Todos los códigos de dimensión sembrados para la normativa vigente en
     * un año (indice.dimension), en orden de actividad/dimensión. Generica
     * a propósito: agregar una dimensión (p.ej. cuando se implemente
     * investigación) es insertar filas en indice.dimension, no tocar esta
     * lista — la usa indice_orquestador::calcularDocente() para no
     * hardcodear el catálogo AD1..AD8.
     *
     * @return list<string>
     */
    static function dimensiones(int $anio): array
    {
        $normativa_id = self::normativa_id($anio);

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT dim.codigo
               FROM indice.dimension dim
               JOIN indice.actividad a ON a.id = dim.actividad_id
              WHERE a.normativa_id = :normativa_id
              ORDER BY a.orden, dim.orden',
            ['normativa_id' => $normativa_id],
        );

        return array_map(static fn (array $fila) => (string) $fila['codigo'], $filas);
    }

    /**
     * Todos los legajo_doc distintos con al menos una ficha (public.ficha).
     * Universo para un cálculo batch (indice_orquestador::calcularTodosLosDocentes()).
     *
     * @return list<int>
     */
    static function legajos(): array
    {
        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT DISTINCT legajo_doc FROM public.ficha ORDER BY legajo_doc',
            [],
        );

        return array_map(static fn (array $fila) => (int) $fila['legajo_doc'], $filas);
    }

    /**
     * La fuente 'desempenio' declara encoding LATIN1 (proyecto.ini) aunque
     * la base está en UTF8: toba::db() devuelve texto acentuado como bytes
     * LATIN1 sueltos (confirmado: SHOW client_encoding = 'LATIN1' en esta
     * conexión), no UTF-8 válido. Sin esto, json_encode() de
     * guardarEvaluacionDimension() falla en silencio (devuelve false) ante
     * cualquier título/institución con tilde. No-op si ya llegó como UTF-8
     * válido (p. ej. datos insertados en el mismo test, que por la misma
     * conexión hacen ida y vuelta sin corromperse).
     */
    private static function asegurar_utf8(?string $valor): ?string
    {
        if ($valor === null || mb_check_encoding($valor, 'UTF-8')) {
            return $valor;
        }

        return mb_convert_encoding($valor, 'UTF-8', 'ISO-8859-1');
    }

    /**
     * Inverso de asegurar_utf8(): antes de escribir texto UTF-8 correcto de
     * vuelta por esta misma conexión LATIN1, hay que volver a bajarlo a
     * LATIN1 — si no, Postgres interpreta los bytes UTF-8 como caracteres
     * LATIN1 sueltos y los guarda doblemente mal codificados (verificado
     * insertando un literal acentuado directo: 'í' terminaba como 'Ã­').
     */
    private static function utf8_a_latin1(?string $valor): ?string
    {
        return $valor === null ? null : mb_convert_encoding($valor, 'ISO-8859-1', 'UTF-8');
    }

    /**
     * evaluacion_item.descripcion es varchar(300): sólo trazabilidad para el
     * detalle que ve el docente (ItemDeclarado.php), el motor no la usa para
     * calcular nada. Encontrado corriendo indice_orquestador::calcularTodosLosDocentes()
     * contra la base real (2026-08-28): 12 de 227 legajos tenían un
     * nombre_proyecto/titulo/denominacion real que superaba los 300
     * caracteres y hacía fallar el INSERT. Trunca en vez de ampliar la
     * columna: es más simple y no hay ningún consumidor que necesite el
     * texto completo hoy. Ya viene convertido a LATIN1 (1 byte = 1 char),
     * así que substr() por bytes alcanza.
     */
    private static function truncar_descripcion(?string $valorLatin1): ?string
    {
        if ($valorLatin1 === null || strlen($valorLatin1) <= 300) {
            return $valorLatin1;
        }

        return substr($valorLatin1, 0, 297) . '...';
    }

    /**
     * Categoría docente y dedicación de un año puntual (public.ficha), ya
     * traducidas a los códigos de indice.categoria_docente/indice.dedicacion
     * que espera indice_repositorio::umbral(). Transversal: la usa cualquier
     * dimensión con umbral que varía por categoría (docs/indice/Mapeo-origen.md,
     * sección Transversales), no sólo AD1.
     *
     * @return array{categoria: string, dedicacion: string}|null null si el
     *   docente no presentó ficha ese año.
     */
    static function categoria_dedicacion(int $legajo, int $anio): ?array
    {
        $ficha_id = self::ficha_id($legajo, $anio);
        if ($ficha_id === null) {
            return null;
        }

        $fila = toba::db('desempenio')->consultar_sentencia(
            'SELECT categoria_id, dedicacion_id FROM public.ficha WHERE id = :ficha_id',
            ['ficha_id' => $ficha_id],
        );

        $categoria_id = $fila[0]['categoria_id'] !== null ? (int) $fila[0]['categoria_id'] : null;
        $dedicacion_id = $fila[0]['dedicacion_id'] !== null ? (int) $fila[0]['dedicacion_id'] : null;

        if ($categoria_id === null || $dedicacion_id === null) {
            throw new RuntimeException(
                "La ficha del legajo {$legajo} en {$anio} no tiene categoría o dedicación cargada.",
            );
        }

        if (!isset(self::CATEGORIAS[$categoria_id])) {
            throw new RuntimeException(
                "categoria_id '{$categoria_id}' (public.ficha, legajo {$legajo}/{$anio}) "
                . 'no está mapeado a un código de indice.categoria_docente.',
            );
        }
        if (!isset(self::DEDICACIONES[$dedicacion_id])) {
            throw new RuntimeException(
                "dedicacion_id '{$dedicacion_id}' (public.ficha, legajo {$legajo}/{$anio}) "
                . 'no está mapeado a un código de indice.dedicacion.',
            );
        }

        return [
            'categoria' => self::CATEGORIAS[$categoria_id],
            'dedicacion' => self::DEDICACIONES[$dedicacion_id],
        ];
    }

    /**
     * Ficha del Informe de Labor de un docente en un año (public.ficha). El
     * mapeo general de "docente/legajo" sigue PENDIENTE; esto resuelve sólo
     * legajo_doc + anio -> ficha.id, que es lo que necesitan items_formacion_academica()
     * y categoria_dedicacion().
     */
    private static function ficha_id(int $legajo, int $anio): ?int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            'SELECT id FROM public.ficha WHERE legajo_doc = :legajo_doc AND anio = :anio',
            ['legajo_doc' => $legajo, 'anio' => $anio],
        );

        return $fila === [] ? null : (int) $fila[0]['id'];
    }

    /**
     * Todos los años con ficha de un docente (public.ficha), ascendente. Es
     * "el período" para el cierre (indice_orquestador::cerrarPeriodo()):
     * sólo los años efectivamente informados, nunca se inventan años faltantes
     * (docs/indice/decisiones-pendientes.md, A5, sigue sin resolver — este
     * módulo por ahora sólo cubre el caso de años presentados).
     *
     * @return list<array{anio: int, ficha_id: int}>
     */
    static function fichas(int $legajo): array
    {
        // anio IS NOT NULL: encontrado corriendo calcularTodosLosDocentes()
        // contra la base real (2026-08-28) -- 2 legajos tenían una ficha
        // extra con anio NULL (además de sus fichas 2024/2025 válidas). Sin
        // este filtro, (int) NULL = 0 se colaba como "año 0" y hacía fallar
        // normativa_id() (make_date(0,1,1)), abortando el cierre de período
        // de TODO el docente, no sólo de esa fila.
        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT id, anio FROM public.ficha WHERE legajo_doc = :legajo AND anio IS NOT NULL ORDER BY anio',
            ['legajo' => $legajo],
        );

        return array_map(
            static fn (array $fila) => ['anio' => (int) $fila['anio'], 'ficha_id' => (int) $fila['id']],
            $filas,
        );
    }

    /**
     * Licencias con y sin goce de un docente en un año, en MESES
     * (docs/indice/Mapeo-origen.md, "Licencias con y sin goce" — resuelto en
     * esta sesión, 2026-08-28). Fuente: public.licencias (ficha_id,
     * tipo_licencia_id, dias, goce). tipo_licencia_id no interviene: la
     * clasificación con/sin goce es la columna 'goce' de cada fila, no el
     * catálogo de tipos (que además está incompleto en los datos reales —
     * irrelevante para este cálculo). dias -> meses: dias/30 (confirmado por
     * el usuario, no hay conversión en la planilla de origen: ahí se carga
     * directo en meses). dias NULL cuenta 0, no se excluye la fila.
     *
     * @return array{lic_con_goce: string, lic_sin_goce: string} redondeado a
     *   2 decimales (numeric(5,2) de evaluacion_periodo).
     */
    static function licencias(int $legajo, int $anio): array
    {
        $ficha_id = self::ficha_id($legajo, $anio);
        if ($ficha_id === null) {
            return ['lic_con_goce' => '0.00', 'lic_sin_goce' => '0.00'];
        }

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT goce, dias FROM public.licencias WHERE ficha_id = :ficha_id',
            ['ficha_id' => $ficha_id],
        );

        $diasConGoce = '0';
        $diasSinGoce = '0';
        foreach ($filas as $fila) {
            $dias = $fila['dias'] !== null ? (string) $fila['dias'] : '0';
            if ((bool) $fila['goce']) {
                $diasConGoce = \Pruebas\Indice\Motor\Bc::add($diasConGoce, $dias);
            } else {
                $diasSinGoce = \Pruebas\Indice\Motor\Bc::add($diasSinGoce, $dias);
            }
        }

        return [
            'lic_con_goce' => \Pruebas\Indice\Motor\Bc::round(\Pruebas\Indice\Motor\Bc::div($diasConGoce, '30'), 2),
            'lic_sin_goce' => \Pruebas\Indice\Motor\Bc::round(\Pruebas\Indice\Motor\Bc::div($diasSinGoce, '30'), 2),
        ];
    }

    /**
     * Componentes de puntaje de una dimensión (p.ej. 'AD1'), en el orden en
     * que EvaluadorComponentes debe aplicarlos (termino, orden). La normativa
     * se resuelve por año: la config es versionada, no se usa "la vigente
     * hoy" para poder reproducir un cálculo con los pesos de su época.
     *
     * @return \Pruebas\Indice\Dto\Componente[]
     */
    static function componentes(string $codigo_dimension, int $anio): array
    {
        $normativa_id = self::normativa_id($anio);

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT c.termino, c.orden, c.tipo, c.campo, c.campo_clave, c.campo_clave_2,
                    c.tabla_valoracion_id, c.divisor, c.divisor_parametro, c.constante, c.params
               FROM indice.componente c
               JOIN indice.dimension d ON d.id = c.dimension_id
               JOIN indice.actividad a ON a.id = d.actividad_id
              WHERE d.codigo = :codigo_dimension
                AND a.normativa_id = :normativa_id
              ORDER BY c.termino, c.orden',
            ['codigo_dimension' => $codigo_dimension, 'normativa_id' => $normativa_id],
        );

        if ($filas === []) {
            throw new RuntimeException(
                "No hay componentes configurados para la dimensión '{$codigo_dimension}' "
                . "en la normativa vigente en {$anio}.",
            );
        }

        $componentes = [];
        foreach ($filas as $fila) {
            $doble_entrada = $fila['campo_clave_2'] !== null;
            $tabla_valoracion = $fila['tabla_valoracion_id'] !== null
                ? self::tabla_valoracion((int) $fila['tabla_valoracion_id'], $doble_entrada)
                : null;

            $divisor = match (true) {
                $fila['divisor'] !== null => (string) $fila['divisor'],
                $fila['divisor_parametro'] !== null => self::valor_parametro($normativa_id, $fila['divisor_parametro']),
                default => null,
            };

            $componentes[] = new \Pruebas\Indice\Dto\Componente(
                tipo: $fila['tipo'],
                termino: (int) $fila['termino'],
                campo: $fila['campo'],
                campoClave: $fila['campo_clave'],
                campoClave2: $fila['campo_clave_2'],
                tablaValoracion: $tabla_valoracion,
                divisor: $divisor,
                constante: $fila['constante'] !== null ? (string) $fila['constante'] : null,
                params: $fila['params'] !== null ? json_decode((string) $fila['params'], true) : null,
            );
        }

        return $componentes;
    }

    /**
     * Umbral (mínimo/superior) de una dimensión para una categoría y
     * dedicación puntuales, con el que Categorizador decide 0/1/3.
     *
     * @return array{minimo: string, superior: string}
     */
    static function umbral(string $codigo_dimension, string $categoria, string $dedicacion, int $anio): array
    {
        $normativa_id = self::normativa_id($anio);

        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT u.minimo, u.superior
               FROM indice.umbral u
               JOIN indice.dimension d ON d.id = u.dimension_id
               JOIN indice.actividad a ON a.id = d.actividad_id
              WHERE d.codigo = :codigo_dimension
                AND a.normativa_id = :normativa_id
                AND u.categoria = :categoria
                AND u.dedicacion = :dedicacion',
            [
                'codigo_dimension' => $codigo_dimension,
                'normativa_id' => $normativa_id,
                'categoria' => $categoria,
                'dedicacion' => $dedicacion,
            ],
        );

        if ($filas === []) {
            throw new RuntimeException(
                "No hay umbral configurado para '{$codigo_dimension}' / categoría '{$categoria}' "
                . "/ dedicación '{$dedicacion}' en la normativa vigente en {$anio}.",
            );
        }

        return ['minimo' => (string) $filas[0]['minimo'], 'superior' => (string) $filas[0]['superior']];
    }

    /**
     * Normativa vigente en un año puntual (indice.normativa.vigencia). No es
     * necesariamente la normativa vigente hoy: permite recalcular un período
     * pasado con los pesos que regían en ese momento. Un año sin normativa
     * cargada, o cubierto por más de una, es un error de configuración.
     */
    private static function normativa_id(int $anio): int
    {
        $filas = toba::db('desempenio')->consultar_sentencia(
            "SELECT id FROM indice.normativa
              WHERE estado <> 'borrador'
                AND vigencia @> make_date(:anio, 1, 1)",
            ['anio' => $anio],
        );

        if ($filas === []) {
            throw new RuntimeException("No hay normativa vigente para el año {$anio}.");
        }
        if (count($filas) > 1) {
            throw new RuntimeException(
                "Hay más de una normativa vigente para el año {$anio}: revisar indice.normativa.",
            );
        }

        return (int) $filas[0]['id'];
    }

    /**
     * Cuadro de valoración (indice.valoracion) de un tabla_valoracion_id, ya
     * armado como lo espera Pruebas\Indice\Dto\Componente::$tablaValoracion:
     * plano [clave => valor] o de doble entrada [clave => [clave_2 => valor]].
     *
     * @return array<string, mixed>
     */
    private static function tabla_valoracion(int $tabla_valoracion_id, bool $doble_entrada): array
    {
        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT clave, clave_2, valor FROM indice.valoracion WHERE tabla_id = :tabla_id ORDER BY clave, clave_2',
            ['tabla_id' => $tabla_valoracion_id],
        );

        $cuadro = [];
        foreach ($filas as $fila) {
            if ($doble_entrada) {
                $cuadro[$fila['clave']][$fila['clave_2']] = (string) $fila['valor'];
            } else {
                $cuadro[$fila['clave']] = (string) $fila['valor'];
            }
        }

        return $cuadro;
    }

    /**
     * Constante de indice.parametro (Che, Cee...) para la normativa vigente
     * en el año evaluado. Alternativa a un divisor literal en el componente.
     */
    private static function valor_parametro(int $normativa_id, string $clave): string
    {
        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT valor FROM indice.parametro WHERE normativa_id = :normativa_id AND clave = :clave',
            ['normativa_id' => $normativa_id, 'clave' => $clave],
        );

        if ($filas === []) {
            throw new RuntimeException("No hay parámetro '{$clave}' configurado para la normativa {$normativa_id}.");
        }

        return (string) $filas[0]['valor'];
    }

    /**
     * Persiste el resultado de una dimensión para un docente/año: crea o
     * extiende la evaluacion "abierta" (provisorio) del docente, y
     * reemplaza evaluacion_dimension/evaluacion_item de ese año/dimensión —
     * recalcular un año ya guardado pisa el resultado anterior, no lo
     * duplica. No toca evaluacion_periodo/evaluacion_actividad/evaluacion_indice.
     *
     * @param list<array{item: \Pruebas\Indice\Dto\ItemDeclarado, puntaje: string}> $itemsConPuntaje
     * @return int evaluacion_id
     */
    static function guardarEvaluacionDimension(
        int $legajo,
        int $anio,
        string $codigo_dimension,
        \Pruebas\Indice\Dto\ResultadoDimension $resultado,
        array $itemsConPuntaje,
    ): int {
        $normativa_id = self::normativa_id($anio);
        $dimension_id = self::dimension_id($codigo_dimension, $normativa_id);
        $evaluacion_id = self::evaluacion_abierta($legajo, $normativa_id, $anio);

        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO indice.evaluacion_dimension (evaluacion_id, dimension_id, anio, puntaje, valoracion, minimo, superior)
             VALUES (:evaluacion_id, :dimension_id, :anio, :puntaje, :valoracion, :minimo, :superior)
             ON CONFLICT (evaluacion_id, dimension_id, anio) DO UPDATE
                SET puntaje = EXCLUDED.puntaje, valoracion = EXCLUDED.valoracion,
                    minimo = EXCLUDED.minimo, superior = EXCLUDED.superior',
            [
                'evaluacion_id' => $evaluacion_id,
                'dimension_id' => $dimension_id,
                'anio' => $anio,
                'puntaje' => $resultado->puntaje,
                'valoracion' => $resultado->valoracion,
                'minimo' => $resultado->minimo,
                'superior' => $resultado->superior,
            ],
        );

        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM indice.evaluacion_item
              WHERE evaluacion_id = :evaluacion_id AND dimension_id = :dimension_id AND anio = :anio',
            ['evaluacion_id' => $evaluacion_id, 'dimension_id' => $dimension_id, 'anio' => $anio],
        );

        foreach ($itemsConPuntaje as $entrada) {
            $item = $entrada['item'];
            toba::db('desempenio')->ejecutar_sentencia(
                'INSERT INTO indice.evaluacion_item
                    (evaluacion_id, dimension_id, anio, origen_tabla, origen_id, descripcion, puntaje, detalle)
                 VALUES (:evaluacion_id, :dimension_id, :anio, :origen_tabla, :origen_id, :descripcion, :puntaje, :detalle)',
                [
                    'evaluacion_id' => $evaluacion_id,
                    'dimension_id' => $dimension_id,
                    'anio' => $anio,
                    'origen_tabla' => $item->origenTabla,
                    'origen_id' => $item->origenId,
                    'descripcion' => self::truncar_descripcion(self::utf8_a_latin1($item->descripcion)),
                    'puntaje' => $entrada['puntaje'],
                    'detalle' => self::utf8_a_latin1(json_encode(
                        ['campos' => $item->campos(), 'detalle' => $item->detalle()],
                        JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
                    )),
                ],
            );
        }

        return $evaluacion_id;
    }

    /**
     * Persiste el cierre de período de un docente: un renglón de
     * evaluacion_periodo por año, con fc/fck ya calculados por
     * CorreccionTemporal (indice_orquestador::cerrarPeriodo() arma $periodos).
     * Recalcular pisa el renglón existente (mismo criterio que
     * guardarEvaluacionDimension): reabrir un año ya cerrado y volver a
     * cerrar el período no duplica filas.
     *
     * @param list<array{anio: int, k: int, ficha_id: int, categoria: string, dedicacion: string, lic_con_goce: string, lic_sin_goce: string, fc: string, fck: string}> $periodos
     */
    static function guardarEvaluacionPeriodo(int $legajo, array $periodos): void
    {
        foreach ($periodos as $periodo) {
            $normativa_id = self::normativa_id($periodo['anio']);
            $evaluacion_id = self::evaluacion_abierta($legajo, $normativa_id, $periodo['anio']);

            toba::db('desempenio')->ejecutar_sentencia(
                'INSERT INTO indice.evaluacion_periodo
                    (evaluacion_id, anio, k, informe_id, informe_leido_en, categoria, dedicacion,
                     lic_con_goce, lic_sin_goce, fc, fck)
                 VALUES (:evaluacion_id, :anio, :k, :informe_id, now(), :categoria, :dedicacion,
                         :lic_con_goce, :lic_sin_goce, :fc, :fck)
                 ON CONFLICT (evaluacion_id, anio) DO UPDATE
                    SET k = EXCLUDED.k, informe_id = EXCLUDED.informe_id,
                        informe_leido_en = EXCLUDED.informe_leido_en,
                        categoria = EXCLUDED.categoria, dedicacion = EXCLUDED.dedicacion,
                        lic_con_goce = EXCLUDED.lic_con_goce, lic_sin_goce = EXCLUDED.lic_sin_goce,
                        fc = EXCLUDED.fc, fck = EXCLUDED.fck',
                [
                    'evaluacion_id' => $evaluacion_id,
                    'anio' => $periodo['anio'],
                    'k' => $periodo['k'],
                    'informe_id' => $periodo['ficha_id'],
                    'categoria' => $periodo['categoria'],
                    'dedicacion' => $periodo['dedicacion'],
                    'lic_con_goce' => $periodo['lic_con_goce'],
                    'lic_sin_goce' => $periodo['lic_sin_goce'],
                    'fc' => $periodo['fc'],
                    'fck' => $periodo['fck'],
                ],
            );
        }
    }

    /**
     * id de indice.dimension para un código dentro de una normativa puntual
     * (el mismo código puede repetirse entre normativas distintas).
     */
    private static function dimension_id(string $codigo_dimension, int $normativa_id): int
    {
        $filas = toba::db('desempenio')->consultar_sentencia(
            'SELECT dim.id
               FROM indice.dimension dim
               JOIN indice.actividad a ON a.id = dim.actividad_id
              WHERE dim.codigo = :codigo_dimension AND a.normativa_id = :normativa_id',
            ['codigo_dimension' => $codigo_dimension, 'normativa_id' => $normativa_id],
        );

        if ($filas === []) {
            throw new RuntimeException("La dimensión '{$codigo_dimension}' no existe en la normativa {$normativa_id}.");
        }

        return (int) $filas[0]['id'];
    }

    /**
     * evaluacion_id de la evaluación "provisorio" del docente para una
     * normativa, extendiendo anio_desde/anio_hasta para cubrir $anio. Sólo
     * puede haber una evaluación abierta por docente/normativa a la vez: el
     * cierre (evaluacion_actividad/evaluacion_indice, período de k años)
     * está pendiente, así que hoy todo año nuevo entra en la misma fila.
     */
    private static function evaluacion_abierta(int $legajo, int $normativa_id, int $anio): int
    {
        $filas = toba::db('desempenio')->consultar_sentencia(
            "SELECT id, anio_desde, anio_hasta
               FROM indice.evaluacion
              WHERE docente_id = :docente_id AND normativa_id = :normativa_id AND estado = 'provisorio'",
            ['docente_id' => $legajo, 'normativa_id' => $normativa_id],
        );

        if ($filas === []) {
            $fila = toba::db('desempenio')->consultar_sentencia(
                "INSERT INTO indice.evaluacion (docente_id, normativa_id, anio_desde, anio_hasta, estado)
                 VALUES (:docente_id, :normativa_id, :anio, :anio, 'provisorio')
                 RETURNING id",
                ['docente_id' => $legajo, 'normativa_id' => $normativa_id, 'anio' => $anio],
            );

            return (int) $fila[0]['id'];
        }

        $evaluacion_id = (int) $filas[0]['id'];
        $anio_desde = (int) $filas[0]['anio_desde'];
        $anio_hasta = (int) $filas[0]['anio_hasta'];

        if ($anio < $anio_desde || $anio > $anio_hasta) {
            toba::db('desempenio')->ejecutar_sentencia(
                'UPDATE indice.evaluacion
                    SET anio_desde = LEAST(anio_desde, :anio), anio_hasta = GREATEST(anio_hasta, :anio)
                  WHERE id = :id',
                ['anio' => $anio, 'id' => $evaluacion_id],
            );
        }

        return $evaluacion_id;
    }
}
