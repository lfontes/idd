<?php

/**
 * Bootstrap para test/datos/: a diferencia de test/indice/ (motor puro, sin
 * Toba), estos tests validan SQL real contra toba::db(), así que necesitan
 * levantar el framework. Mismo camino que usa bin/toba para acceso por
 * consola (toba_nucleo::iniciar_contexto_desde_consola), sin procesar
 * ningún item — sólo deja armada la conexión a base.
 *
 * Requiere correr dentro del contenedor `test` (Postgres sólo es alcanzable
 * en la red de Docker):
 *   docker exec -w <project-root> test php vendor/bin/phpunit -c phpunit.datos.xml
 */

require_once __DIR__ . '/../../vendor/autoload.php';

$proyecto_dir = dirname(__DIR__, 2);
$_SERVER['TOBA_DIR'] = dirname($proyecto_dir, 2);
$_SERVER['TOBA_INSTANCIA'] = 'desarrollo';

ini_set('include_path', ini_get('include_path') . ':.:' . $_SERVER['TOBA_DIR'] . '/php');
require_once('nucleo/toba_nucleo.php');

toba_nucleo::instancia()->iniciar_contexto_desde_consola('desarrollo', 'pruebas');

// indice_repositorio.php / indice_orquestador.php no son componentes del
// editor de Toba, así que no están en el autoload generado
// (php/pruebas_autoload.php, no editar a mano).
require_once __DIR__ . '/../../php/datos/indice_repositorio.php';
require_once __DIR__ . '/../../php/datos/indice_orquestador.php';
