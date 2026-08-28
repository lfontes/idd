<?php

declare(strict_types=1);

namespace Pruebas\Indice\Test\Apoyo;

use Pruebas\Indice\Dto\Componente;

/**
 * Mismo catálogo de componentes y umbrales que sql/indice/02_siembra_docencia.sql,
 * pero como DTOs en memoria: el repositorio que los arme desde la base todavía
 * no existe (a propósito, ver .claude/rules/indice.md), así que los tests de
 * orquestación necesitan esta config para poder correr.
 */
final class ConfiguracionAD
{
    /** @return array<string, Componente[]> */
    public static function componentes(): array
    {
        return [
            'AD1' => [
                new Componente(
                    tipo: 'producto',
                    campo: 'cantidad',
                    campoClave: 'tipo_formacion',
                    tablaValoracion: [
                        'Pregrado' => '1', 'Grado' => '3', 'Posdoctorado' => '6', 'Doctorado' => '5',
                        'Maestría' => '3', 'Especialización' => '1', 'Diplomado' => '0.5',
                    ],
                ),
            ],
            'AD2' => [
                new Componente(
                    tipo: 'divisor_condicional',
                    termino: 1,
                    campo: 'horas',
                    params: [
                        'reglas' => [
                            ['cuando' => ['curso' => 1, 'aprobado' => 1], 'divisor' => 25],
                            ['cuando' => ['curso' => 1, 'asistencia' => 1], 'divisor' => 50],
                            ['cuando' => ['taller' => 1, 'aprobado' => 1], 'divisor' => 25],
                            ['cuando' => ['taller' => 1, 'asistencia' => 1], 'divisor' => 50],
                            ['cuando' => ['form_obligatoria' => 1, 'aprobado' => 1], 'divisor' => 25],
                        ],
                        'defecto' => 0,
                    ],
                ),
                new Componente(tipo: 'condicional', termino: 2, campoClave: 'pasantia', constante: '5'),
                new Componente(tipo: 'condicional', termino: 3, campoClave: 'distincion', constante: '0.5'),
            ],
            'AD3' => [
                new Componente(tipo: 'proporcion', termino: 1, campo: 'carga_horaria', divisor: '90'),
                new Componente(tipo: 'proporcion', termino: 1, campo: 'estudiantes_inscriptos', divisor: '90'),
                new Componente(
                    tipo: 'lookup',
                    termino: 1,
                    campoClave: 'tipo_espacio',
                    tablaValoracion: ['1' => '2', '2' => '3', '3' => '5', '4' => '12'],
                ),
            ],
            'AD4' => [
                new Componente(tipo: 'directo', termino: 1, campo: 'creditos'),
                new Componente(tipo: 'proporcion', termino: 1, campo: 'participacion', divisor: '100'),
                new Componente(
                    tipo: 'lookup',
                    termino: 1,
                    campoClave: 'carrera',
                    tablaValoracion: [
                        'Doctorado' => '7', 'Maestría' => '5', 'Especialización' => '3',
                        'Diplomatura' => '1', 'Diplomado' => '0.5',
                    ],
                ),
            ],
            'AD5' => [
                new Componente(
                    tipo: 'lookup',
                    termino: 1,
                    campoClave: 'tipo_participacion',
                    tablaValoracion: ['Asistente' => '1', 'Expositor' => '3', 'Organización' => '4', 'Evaluador' => '5'],
                ),
                new Componente(
                    tipo: 'lookup',
                    termino: 2,
                    campoClave: 'tipo_presentacion',
                    campoClave2: 'referato',
                    tablaValoracion: [
                        'Resumen' => ['0' => '1', '1' => '3'],
                        'Trabajo Extendido' => ['0' => '3', '1' => '5'],
                        'Trabajo Completo' => ['0' => '5', '1' => '8'],
                    ],
                ),
            ],
            'AD6' => [
                new Componente(
                    tipo: 'lookup',
                    campoClave: 'tipo_participacion',
                    tablaValoracion: ['Director' => '8', 'Codirector' => '5', 'Participante' => '1', 'Evaluador' => '5'],
                ),
            ],
            'AD7' => [
                new Componente(
                    tipo: 'lookup',
                    campoClave: 'categoria_rrhh',
                    tablaValoracion: ['Docente' => '10', 'Adscripto' => '5', 'Pasante' => '3', 'Concurrencia' => '3'],
                ),
            ],
            'AD8' => [
                new Componente(
                    tipo: 'lookup',
                    termino: 1,
                    campoClave: 'material',
                    tablaValoracion: ['Apuntes de clase' => '8', 'Trabajo Práctico' => '5', 'Entorno Virtual' => '8'],
                ),
                new Componente(
                    tipo: 'lookup',
                    termino: 1,
                    campoClave: 'tarea',
                    tablaValoracion: ['Desarrollo' => '5', 'Actualización' => '1'],
                ),
            ],
        ];
    }

    /**
     * Umbral por dimensión y "categoria|dedicacion". AD1 varía por categoría;
     * AD2..AD7 son iguales para toda categoría/dedicación; AD8 min=superior=19
     * ([REVISAR] 19 vs 20 en decisiones-pendientes.md, no lo resolvemos acá).
     *
     * @return array<string, array<string, array{minimo: string, superior: string}>>
     */
    public static function umbral(): array
    {
        $categorias = ['T', 'AS', 'AJ', 'JTP', 'AY1', 'AY2'];
        $dedicaciones = ['EX', 'SE', 'DS'];

        $parejo = static function (string $minimo, string $superior) use ($categorias, $dedicaciones): array {
            $filas = [];
            foreach ($categorias as $categoria) {
                foreach ($dedicaciones as $dedicacion) {
                    $filas["{$categoria}|{$dedicacion}"] = ['minimo' => $minimo, 'superior' => $superior];
                }
            }
            return $filas;
        };

        $ad1 = [];
        $porCategoria = ['T' => ['6', '8'], 'AS' => ['6', '8'], 'AJ' => ['6', '8'],
                          'JTP' => ['3', '5'], 'AY1' => ['1', '3'], 'AY2' => ['0', '2']];
        foreach ($porCategoria as $categoria => [$minimo, $superior]) {
            foreach ($dedicaciones as $dedicacion) {
                $ad1["{$categoria}|{$dedicacion}"] = ['minimo' => $minimo, 'superior' => $superior];
            }
        }

        return [
            'AD1' => $ad1,
            'AD2' => $parejo('1', '5'),
            'AD3' => $parejo('1', '5'),
            'AD4' => $parejo('1', '2'),
            'AD5' => $parejo('1', '5'),
            'AD6' => $parejo('1', '8'),
            'AD7' => $parejo('1', '15'),
            'AD8' => $parejo('19', '19'),
        ];
    }

    public static function fixture(): array
    {
        static $fixture = null;
        if ($fixture === null) {
            $ruta = __DIR__ . '/../../../docs/indice/fixtures/caso_docencia.json';
            $fixture = json_decode((string) file_get_contents($ruta), true, flags: JSON_THROW_ON_ERROR);
        }
        return $fixture;
    }
}
