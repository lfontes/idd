-- =============================================================================
-- Índice de Desempeño Docente - FCA / UNCuyo
-- Esquema de configuración y resultados
-- PostgreSQL >= 12 · se instala dentro de la base del proyecto Toba
-- =============================================================================
-- Convenciones:
--   * Todo vive en el esquema "indice" para no mezclarse con las tablas apex_*
--     de Toba ni con las del Informe de Labor.
--   * Los puntajes usan numeric, nunca float: el redondeo binario no es
--     admisible en una evaluación con efecto laboral.
--   * La configuración se versiona por normativa. Un resultado ya calculado
--     debe poder reproducirse años después con los pesos de su época.
-- =============================================================================

CREATE SCHEMA IF NOT EXISTS indice;
SET search_path TO indice, public;


-- =============================================================================
-- 1. TABLAS DE REFERENCIA
-- =============================================================================

CREATE TABLE categoria_docente (
    codigo       varchar(5)   PRIMARY KEY,          -- T, AS, AJ, JTP, AY1, AY2
    nombre       varchar(60)  NOT NULL,
    orden        smallint     NOT NULL,
    UNIQUE (orden)
);

CREATE TABLE dedicacion (
    codigo       varchar(5)   PRIMARY KEY,          -- EX, SE, DS
    nombre       varchar(60)  NOT NULL,
    orden        smallint     NOT NULL,
    UNIQUE (orden)
);

-- Combinaciones cargo/dedicación efectivamente vigentes.
-- Evita configurar umbrales para pares que no existen (p. ej. AY2 con EX).
CREATE TABLE cargo (
    categoria    varchar(5)   NOT NULL REFERENCES categoria_docente,
    dedicacion   varchar(5)   NOT NULL REFERENCES dedicacion,
    PRIMARY KEY (categoria, dedicacion)
);


-- =============================================================================
-- 2. NORMATIVA Y ESTRUCTURA DE EVALUACIÓN
-- =============================================================================

CREATE TABLE normativa (
    id           serial       PRIMARY KEY,
    codigo       varchar(40)  NOT NULL UNIQUE,      -- 'ORD-CD-2025-14'
    descripcion  text         NOT NULL,
    vigencia     daterange    NOT NULL,
    estado       varchar(15)  NOT NULL DEFAULT 'borrador'
                 CHECK (estado IN ('borrador', 'vigente', 'derogada')),
    creado_en    timestamptz  NOT NULL DEFAULT now(),
    -- Dos normativas vigentes no pueden solaparse en el tiempo.
    EXCLUDE USING gist (vigencia WITH &&) WHERE (estado = 'vigente')
);

CREATE TABLE actividad (
    id            serial       PRIMARY KEY,
    normativa_id  integer      NOT NULL REFERENCES normativa ON DELETE CASCADE,
    codigo        varchar(5)   NOT NULL,            -- AD, AI, AE, AG
    nombre        varchar(120) NOT NULL,
    orden         smallint     NOT NULL,
    UNIQUE (normativa_id, codigo),
    UNIQUE (normativa_id, orden)
);

CREATE TABLE dimension (
    id            serial       PRIMARY KEY,
    actividad_id  integer      NOT NULL REFERENCES actividad ON DELETE CASCADE,
    codigo        varchar(8)   NOT NULL,            -- AD1 .. AD8, AI1 .. AI6
    nombre        varchar(200) NOT NULL,
    orden         smallint     NOT NULL,
    -- Identificador del proveedor de ítems: qué consulta del Informe de Labor
    -- alimenta esta dimensión. Lo resuelve el repositorio, no el motor.
    proveedor     varchar(80)  NOT NULL,
    UNIQUE (actividad_id, codigo),
    UNIQUE (actividad_id, orden)
);


-- =============================================================================
-- 3. TABLAS DE VALORACIÓN (los "Cuadros" del Excel)
-- =============================================================================
-- Un cuadro = una fila en tabla_valoracion; sus filas = valoracion.
-- clave_2 cubre los cuadros de doble entrada (p. ej. tipo de presentación
-- cruzado con referato sí/no en AD5).

CREATE TABLE tabla_valoracion (
    id            serial       PRIMARY KEY,
    normativa_id  integer      NOT NULL REFERENCES normativa ON DELETE CASCADE,
    codigo        varchar(50)  NOT NULL,            -- 'formacion_academica'
    descripcion   varchar(200) NOT NULL,            -- 'Cuadro 2.4.1.1: Puntaje (w)...'
    UNIQUE (normativa_id, codigo)
);

CREATE TABLE valoracion (
    tabla_id      integer      NOT NULL REFERENCES tabla_valoracion ON DELETE CASCADE,
    clave         varchar(60)  NOT NULL,            -- 'Doctorado', 'Q1', 'PICT'
    clave_2       varchar(60)  NOT NULL DEFAULT '', -- '' cuando el cuadro es simple
    valor         numeric(14,6) NOT NULL,
    PRIMARY KEY (tabla_id, clave, clave_2)
);

-- Constantes sueltas de la normativa (Che, Cee, divisor de hs/semana, etc.)
CREATE TABLE parametro (
    normativa_id  integer      NOT NULL REFERENCES normativa ON DELETE CASCADE,
    clave         varchar(50)  NOT NULL,            -- 'Che', 'Cee'
    valor         numeric(14,6) NOT NULL,
    descripcion   varchar(200),
    PRIMARY KEY (normativa_id, clave)
);


-- =============================================================================
-- 4. COMPONENTES DE PUNTAJE
-- =============================================================================
-- El puntaje de un ítem es una SUMA DE PRODUCTOS:
--
--     puntaje = SUMA sobre términos ( PRODUCTO de los componentes del término )
--
-- Los componentes con el mismo "termino" se multiplican entre sí; los términos
-- se suman. Con eso quedan cubiertas tanto las dimensiones aditivas (AD5:
-- participación + presentación) como las multiplicativas (AD8: valor del
-- material x valor de la tarea; AD3: carga/Che x inscriptos/Cee x complejidad).
--
-- Catálogo cerrado de formas:
--   lookup                 campo_clave -> valor de un cuadro
--                          (con campo_clave_2 resuelve cuadros de doble entrada)
--   banderas_excluyentes   varias columnas 0/1, sólo una marcada -> su valor
--   banderas_acumulativas  varias columnas 0/1, todas las marcadas suman
--   producto               campo (cantidad) x valor del cuadro por campo_clave
--   proporcion             campo / divisor
--   condicional            si campo_clave está marcado -> constante, si no 0
--   divisor_condicional    campo / divisor elegido por combinación de banderas
--   directo                el campo se usa tal cual

CREATE TABLE componente (
    id                  serial       PRIMARY KEY,
    dimension_id        integer      NOT NULL REFERENCES dimension ON DELETE CASCADE,
    -- '' = aplica a todos los ítems de la dimensión.
    -- Con valor, sólo a los ítems de ese subtipo (AI4: revista, divulgación, libro).
    -- No se usa NULL: en PostgreSQL dos NULL no colisionan y la restricción
    -- de unicidad de más abajo dejaría pasar componentes duplicados.
    tipo_item           varchar(40)  NOT NULL DEFAULT '',
    -- Los componentes de un mismo término se multiplican; los términos se suman.
    termino             smallint     NOT NULL DEFAULT 1,
    orden               smallint     NOT NULL,      -- orden dentro del término
    tipo                varchar(25)  NOT NULL CHECK (tipo IN (
                            'lookup', 'banderas_excluyentes', 'banderas_acumulativas',
                            'producto', 'proporcion', 'condicional',
                            'divisor_condicional', 'directo')),
    -- Campo numérico que consume el componente (cantidad, horas, créditos).
    campo               varchar(60),
    -- Campo cuyo valor se busca como clave en el cuadro de valoración.
    campo_clave         varchar(60),
    campo_clave_2       varchar(60),                -- cuadros de doble entrada
    tabla_valoracion_id integer      REFERENCES tabla_valoracion ON DELETE RESTRICT,
    divisor             numeric(14,6) CHECK (divisor IS NULL OR divisor <> 0),
    -- Alternativa a "divisor": clave de indice.parametro, para los divisores
    -- que la normativa define como constantes con nombre (Che, Cee).
    divisor_parametro   varchar(50),
    constante           numeric(14,6),
    -- Sólo para 'divisor_condicional': reglas de selección del divisor.
    params              jsonb,
    UNIQUE (dimension_id, tipo_item, termino, orden),

    CONSTRAINT componente_coherente CHECK (
        CASE tipo
            WHEN 'lookup'                THEN campo_clave IS NOT NULL AND tabla_valoracion_id IS NOT NULL
            WHEN 'banderas_excluyentes'  THEN tabla_valoracion_id IS NOT NULL
            WHEN 'banderas_acumulativas' THEN tabla_valoracion_id IS NOT NULL
            WHEN 'producto'              THEN campo IS NOT NULL AND campo_clave IS NOT NULL
                                              AND tabla_valoracion_id IS NOT NULL
            WHEN 'proporcion'            THEN campo IS NOT NULL
                                              AND (divisor IS NOT NULL OR divisor_parametro IS NOT NULL)
            WHEN 'condicional'           THEN campo_clave IS NOT NULL AND constante IS NOT NULL
            WHEN 'divisor_condicional'   THEN campo IS NOT NULL AND params IS NOT NULL
            WHEN 'directo'               THEN campo IS NOT NULL
        END
    )
);

CREATE INDEX ON componente (dimension_id, tipo_item, termino, orden);


-- =============================================================================
-- 5. UMBRALES
-- =============================================================================
-- Una sola tabla para dos usos que el Excel duplicaba:
--   a) categorizar la dimensión:  PD < minimo -> 0
--                                 minimo <= PD <= superior -> 1
--                                 PD > superior -> 3
--   b) umbral básico de la actividad: SUM(minimo) sobre sus dimensiones.
--
-- Cuando la normativa no distingue por categoría docente (AD2..AD8), se cargan
-- igual las filas de todas las categorías con el mismo valor. Cuesta seis filas
-- y elimina todo condicional del motor.

CREATE TABLE umbral (
    dimension_id  integer       NOT NULL REFERENCES dimension ON DELETE CASCADE,
    categoria     varchar(5)    NOT NULL,
    dedicacion    varchar(5)    NOT NULL,
    minimo        numeric(14,6) NOT NULL,
    superior      numeric(14,6) NOT NULL,
    PRIMARY KEY (dimension_id, categoria, dedicacion),
    FOREIGN KEY (categoria, dedicacion) REFERENCES cargo,
    CONSTRAINT umbral_orden CHECK (superior >= minimo)
);


-- =============================================================================
-- 6. RESULTADOS
-- =============================================================================
-- Se persiste el cálculo completo, nunca se recalcula al mostrar. El detalle
-- jsonb de cada ítem guarda los pesos efectivamente aplicados: es lo que
-- permite explicarle a un docente en 2031 por qué obtuvo 1 y no 3.

CREATE TABLE evaluacion (
    id             serial       PRIMARY KEY,
    -- Un cálculo por docente y período de k años. NO es la ficha del Informe
    -- de Labor: esa es anual y se vincula desde evaluacion_periodo.
    -- FK al legajo/persona del Informe de Labor. Ajustar al nombre real.
    docente_id     integer      NOT NULL,
    normativa_id   integer      NOT NULL REFERENCES normativa,
    anio_desde     smallint     NOT NULL,
    anio_hasta     smallint     NOT NULL,
    estado         varchar(15)  NOT NULL DEFAULT 'provisorio'
                   CHECK (estado IN ('provisorio', 'cerrado', 'anulado')),
    calculado_en   timestamptz,
    calculado_por  varchar(60),                     -- usuario Toba
    cerrado_en     timestamptz,
    observaciones  text,
    CONSTRAINT evaluacion_periodo_valido CHECK (anio_hasta >= anio_desde),
    UNIQUE (docente_id, normativa_id, anio_desde, anio_hasta)
);

CREATE INDEX ON evaluacion (docente_id);

-- Situación de revista y licencias de cada año del período.
-- La corrección temporal depende de la serie completa, no del año aislado.
--
-- Un renglón por año = una ficha del Informe de Labor. La evaluación agrupa
-- las k fichas del período; no se confunde con ninguna de ellas. La categoría
-- y la dedicación se toman de la ficha de cada año, porque cambian dentro del
-- período (en el modelo del Excel el docente pasa de AJ a T en el año 2).
CREATE TABLE evaluacion_periodo (
    evaluacion_id  integer       NOT NULL REFERENCES evaluacion ON DELETE CASCADE,
    anio           smallint      NOT NULL,
    k              smallint      NOT NULL,          -- 1..n, orden dentro del período
    -- Ficha del Informe de Labor de ese año. NULL = el docente no presentó
    -- informe; el año computa cero puntos y queda registrado como tal.
    informe_id     integer,
    -- Marca de tiempo de la ficha al momento del cálculo: si después se
    -- reabre y se edita, se detecta que el resultado quedó desactualizado.
    informe_leido_en timestamptz,
    categoria      varchar(5)    NOT NULL REFERENCES categoria_docente,
    dedicacion     varchar(5)    NOT NULL REFERENCES dedicacion,
    lic_con_goce   numeric(5,2)  NOT NULL DEFAULT 0 CHECK (lic_con_goce  BETWEEN 0 AND 12),
    lic_sin_goce   numeric(5,2)  NOT NULL DEFAULT 0 CHECK (lic_sin_goce  BETWEEN 0 AND 12),
    fc             numeric(14,6) NOT NULL,          -- 1 - (lcg + lsg) / 12
    fck            numeric(14,6) NOT NULL,          -- factor de corrección acumulado
    PRIMARY KEY (evaluacion_id, anio),
    UNIQUE (evaluacion_id, k),
    -- Una misma ficha no puede alimentar dos años de la misma evaluación.
    UNIQUE (evaluacion_id, informe_id),
    CONSTRAINT licencias_no_superan_el_anio CHECK (lic_con_goce + lic_sin_goce <= 12)
);

-- Un renglón por ítem declarado y puntuado. Es la traza fina.
CREATE TABLE evaluacion_item (
    id             bigserial     PRIMARY KEY,
    evaluacion_id  integer       NOT NULL REFERENCES evaluacion ON DELETE CASCADE,
    dimension_id   integer       NOT NULL REFERENCES dimension,
    anio           smallint      NOT NULL,
    tipo_item      varchar(40),
    -- Origen en el Informe de Labor, para poder volver al dato cargado.
    origen_tabla   varchar(60),
    origen_id      integer,
    descripcion    varchar(300),                    -- lo que ve el docente en el detalle
    puntaje        numeric(14,6) NOT NULL,
    -- {"componentes":[{"tipo":"lookup","campo":"tipo","clave":"PICT","valor":5}, ...]}
    detalle        jsonb         NOT NULL
);

CREATE INDEX ON evaluacion_item (evaluacion_id, dimension_id, anio);

CREATE TABLE evaluacion_dimension (
    evaluacion_id  integer       NOT NULL REFERENCES evaluacion ON DELETE CASCADE,
    dimension_id   integer       NOT NULL REFERENCES dimension,
    anio           smallint      NOT NULL,
    puntaje        numeric(14,6) NOT NULL,          -- PD / PI
    valoracion     smallint      NOT NULL CHECK (valoracion IN (0, 1, 3)),  -- AD / AI
    -- Umbrales aplicados, copiados al momento del cálculo.
    minimo         numeric(14,6) NOT NULL,
    superior       numeric(14,6) NOT NULL,
    PRIMARY KEY (evaluacion_id, dimension_id, anio)
);

CREATE TABLE evaluacion_actividad (
    evaluacion_id     integer       NOT NULL REFERENCES evaluacion ON DELETE CASCADE,
    actividad_id      integer       NOT NULL REFERENCES actividad,
    anio              smallint      NOT NULL,
    puntaje           numeric(14,6) NOT NULL,       -- PDpjl / PIpjl
    umbral            numeric(14,6) NOT NULL,       -- UBDjl / UBIjl
    valoracion_total  numeric(14,6) NOT NULL,       -- suma de las valoraciones 0/1/3
    valoracion_media  numeric(14,6) NOT NULL,
    indice_parcial    numeric(14,6) NOT NULL,       -- IA del año
    indice_acumulado  numeric(14,6) NOT NULL,       -- IA en k años
    PRIMARY KEY (evaluacion_id, actividad_id, anio),
    CONSTRAINT umbral_no_nulo CHECK (umbral > 0)    -- guarda el denominador
);

-- Índice global del docente para el período.
-- La fórmula de consolidación entre actividades está pendiente de definición
-- normativa; por eso se guarda el método aplicado junto al resultado.
CREATE TABLE evaluacion_indice (
    evaluacion_id  integer       PRIMARY KEY REFERENCES evaluacion ON DELETE CASCADE,
    indice         numeric(14,6) NOT NULL,
    metodo         varchar(40)   NOT NULL,
    detalle        jsonb         NOT NULL
);


-- =============================================================================
-- 7. VISTAS DE LECTURA
-- =============================================================================
-- Sobre resultados ya calculados, nunca calculan nada.

CREATE VIEW v_matriz_dimension AS
SELECT e.id            AS evaluacion_id,
       e.docente_id,
       a.codigo        AS actividad,
       d.codigo        AS dimension,
       d.orden         AS dimension_orden,
       ed.anio,
       ed.puntaje,
       ed.valoracion
  FROM evaluacion            e
  JOIN evaluacion_dimension ed ON ed.evaluacion_id = e.id
  JOIN dimension             d ON d.id  = ed.dimension_id
  JOIN actividad             a ON a.id  = d.actividad_id;

-- Control de coherencia: el umbral de la actividad debe ser la suma de los
-- mínimos de sus dimensiones. Si devuelve filas, la configuración está mal.
CREATE VIEW v_control_umbrales AS
SELECT a.id AS actividad_id, a.codigo, u.categoria, u.dedicacion,
       SUM(u.minimo) AS umbral_calculado
  FROM actividad a
  JOIN dimension d ON d.actividad_id = a.id
  JOIN umbral    u ON u.dimension_id = d.id
 GROUP BY a.id, a.codigo, u.categoria, u.dedicacion;
