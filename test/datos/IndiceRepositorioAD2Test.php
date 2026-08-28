<?php

declare(strict_types=1);

/**
 * Test de integración: AD2 — Actualización y capacitación en docencia
 * (docs/indice/mapeo-origen.md, sección AD2). Sin mocks, contra una ficha
 * real (de prueba) en la base `desempenio`.
 *
 * Mapeo confirmado por el usuario (sesión 2026-08-28), no inferido:
 *  - certificado: 0=Asistencia, 1=Aprobación.
 *  - tipo_act (public.c32_actualizacion_tipos, por id): 1=Curso, 2=Taller,
 *    3=Formación Obligatoria, 4=Pasantía, 5=Distinción, 6=Seminario
 *    (Pasantía/Distinción agregadas al catálogo por el usuario en esta
 *    sesión; antes no existían). Seminario se asimila a Curso.
 *
 * Corre bajo test/datos/bootstrap.php (levanta Toba):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */
final class IndiceRepositorioAD2Test extends \PHPUnit\Framework\TestCase
{
    private const LEGAJO = 900000005;
    private const DNI = 90000005;
    private const ANIO = 2025;

    protected function setUp(): void
    {
        $this->limpiar();
    }

    protected function tearDown(): void
    {
        $this->limpiar();
    }

    public function test_items_capacitacion_docente_traduce_las_banderas(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearActualizacion($ficha_id, tipo_act: 1, certificado: 1, horas: 25, nombre: 'Curso aprobado'); // Curso
        $this->crearActualizacion($ficha_id, tipo_act: 2, certificado: 0, horas: 50, nombre: 'Taller con asistencia'); // Taller
        $this->crearActualizacion($ficha_id, tipo_act: 3, certificado: 1, horas: 25, nombre: 'Formación obligatoria'); // Form. obligatoria
        $this->crearActualizacion($ficha_id, tipo_act: 4, certificado: 0, horas: 0, nombre: 'Pasantía'); // Pasantía
        $this->crearActualizacion($ficha_id, tipo_act: 5, certificado: 0, horas: 0, nombre: 'Distinción'); // Distinción
        $this->crearActualizacion($ficha_id, tipo_act: 6, certificado: 1, horas: 25, nombre: 'Seminario aprobado'); // Seminario -> se asimila a Curso

        $items = indice_repositorio::items_capacitacion_docente(self::LEGAJO, self::ANIO);

        self::assertCount(6, $items);

        // Curso, aprobado
        self::assertSame('1', $items[0]->valor('curso'));
        self::assertSame('0', $items[0]->valor('taller'));
        self::assertSame('0', $items[0]->valor('form_obligatoria'));
        self::assertSame('0', $items[0]->valor('pasantia'));
        self::assertSame('0', $items[0]->valor('distincion'));
        self::assertSame('1', $items[0]->valor('aprobado'));
        self::assertSame('0', $items[0]->valor('asistencia'));
        self::assertSame('25', $items[0]->valor('horas'));

        // Taller, asistencia (sin certificado)
        self::assertSame('1', $items[1]->valor('taller'));
        self::assertSame('0', $items[1]->valor('aprobado'));
        self::assertSame('1', $items[1]->valor('asistencia'));

        // Formación obligatoria, aprobada
        self::assertSame('1', $items[2]->valor('form_obligatoria'));
        self::assertSame('1', $items[2]->valor('aprobado'));

        // Pasantía
        self::assertSame('1', $items[3]->valor('pasantia'));
        self::assertSame('0', $items[3]->valor('curso'));

        // Distinción
        self::assertSame('1', $items[4]->valor('distincion'));

        // Seminario -> se asimila a Curso (decisión confirmada por el usuario)
        self::assertSame('1', $items[5]->valor('curso'));
        self::assertSame('0', $items[5]->valor('taller'));

        self::assertSame('c32_actualizacion', $items[0]->origenTabla);
        self::assertSame('Curso aprobado', $items[0]->descripcion);
    }

    public function test_componentes_de_ad2_reproduce_las_reglas_sembradas(): void
    {
        $componentes = indice_repositorio::componentes('AD2', self::ANIO);

        self::assertCount(3, $componentes);

        $divisorCondicional = $componentes[0];
        self::assertSame('divisor_condicional', $divisorCondicional->tipo);
        self::assertSame('horas', $divisorCondicional->campo);
        self::assertSame(
            [
                ['cuando' => ['curso' => 1, 'aprobado' => 1], 'divisor' => 25],
                ['cuando' => ['curso' => 1, 'asistencia' => 1], 'divisor' => 50],
                ['cuando' => ['taller' => 1, 'aprobado' => 1], 'divisor' => 25],
                ['cuando' => ['taller' => 1, 'asistencia' => 1], 'divisor' => 50],
                ['cuando' => ['aprobado' => 1, 'form_obligatoria' => 1], 'divisor' => 25],
            ],
            $divisorCondicional->params['reglas'],
        );
        self::assertSame(0, $divisorCondicional->params['defecto']);

        self::assertSame('condicional', $componentes[1]->tipo);
        self::assertSame('pasantia', $componentes[1]->campoClave);
        self::assertSame('5.000000', $componentes[1]->constante);

        self::assertSame('condicional', $componentes[2]->tipo);
        self::assertSame('distincion', $componentes[2]->campoClave);
        self::assertSame('0.500000', $componentes[2]->constante);
    }

    public function test_umbral_de_ad2_jtp_ds(): void
    {
        self::assertSame(
            ['minimo' => '1.000000', 'superior' => '5.000000'],
            indice_repositorio::umbral('AD2', 'JTP', 'DS', self::ANIO),
        );
    }

    /**
     * Punta a punta: 6 ítems (uno por bandera + Seminario asimilado a Curso),
     * horas elegidas como múltiplo exacto del divisor para que cada término
     * de horas dé 1.000000. PD2 = 1+1+1+5+0.5+1 = 9.5 -> supera el superior
     * (5) de JTP/DS -> AD2 = 3.
     */
    public function test_calculo_real_de_ad2_de_punta_a_punta(): void
    {
        $ficha_id = $this->crearFicha();
        $this->crearActualizacion($ficha_id, tipo_act: 1, certificado: 1, horas: 25, nombre: 'Curso aprobado');
        $this->crearActualizacion($ficha_id, tipo_act: 2, certificado: 0, horas: 50, nombre: 'Taller con asistencia');
        $this->crearActualizacion($ficha_id, tipo_act: 3, certificado: 1, horas: 25, nombre: 'Formación obligatoria');
        $this->crearActualizacion($ficha_id, tipo_act: 4, certificado: 0, horas: 0, nombre: 'Pasantía');
        $this->crearActualizacion($ficha_id, tipo_act: 5, certificado: 0, horas: 0, nombre: 'Distinción');
        $this->crearActualizacion($ficha_id, tipo_act: 6, certificado: 1, horas: 25, nombre: 'Seminario aprobado');

        $items = indice_repositorio::items_capacitacion_docente(self::LEGAJO, self::ANIO);
        $componentes = indice_repositorio::componentes('AD2', self::ANIO);
        $umbral = indice_repositorio::umbral('AD2', 'JTP', 'DS', self::ANIO);

        $evaluador = new \Pruebas\Indice\Motor\EvaluadorComponentes();
        $puntaje = '0';
        foreach ($items as $item) {
            $puntaje = bcadd($puntaje, $evaluador->evaluarItem($componentes, $item), 6);
        }

        self::assertSame('9.500000', $puntaje);
        self::assertSame(
            3,
            (new \Pruebas\Indice\Motor\Categorizador())->categorizar($puntaje, $umbral['minimo'], $umbral['superior']),
        );
    }

    private function crearFicha(): int
    {
        $fila = toba::db('desempenio')->consultar_sentencia(
            "INSERT INTO public.ficha (dni_doc, legajo_doc, user_id, anio, categoria_id, dedicacion_id)
             VALUES (:dni, :legajo, 'test_indice_ad2', :anio, 4, 3)
             RETURNING id",
            ['dni' => self::DNI, 'legajo' => self::LEGAJO, 'anio' => self::ANIO],
        );
        return (int) $fila[0]['id'];
    }

    private function crearActualizacion(int $ficha_id, int $tipo_act, int $certificado, int $horas, string $nombre): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'INSERT INTO public.c32_actualizacion (ficha_id, tipo_act, nombre, certificado, horas)
             VALUES (:ficha_id, :tipo_act, :nombre, :certificado, :horas)',
            [
                'ficha_id' => $ficha_id,
                'tipo_act' => $tipo_act,
                'nombre' => $nombre,
                'certificado' => $certificado,
                'horas' => $horas,
            ],
        );
    }

    private function limpiar(): void
    {
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.c32_actualizacion
              WHERE ficha_id IN (SELECT id FROM public.ficha WHERE legajo_doc = :legajo)',
            ['legajo' => self::LEGAJO],
        );
        toba::db('desempenio')->ejecutar_sentencia(
            'DELETE FROM public.ficha WHERE legajo_doc = :legajo',
            ['legajo' => self::LEGAJO],
        );
    }
}
