<?php
/*
 *   _____ ___ _ _____
 *  |   __|   | |   __|
 *  |__   | | | |   __|
 *  |_____|_|___|__|
 *
 *        Version 0.0.4
 *
 *  Jonas Hansen <sasin91@gmail.com>
 *
 */

/**
 * Shared configures,
 * mostly global variables and constants
 */
include('../shared.php');

/**
 * System PSR-0 AutoLoader
 */
require_once(BASE_PATH . DS . '/app/Libraries/vendor/autoload.php');

/**
 * Bind config to variable
 */
$config = Config::get('Config');

/**
 * Run install if not installed.
 */
if ($config['Installed'] === false) {
    require_once 'install.php';
    return new Install($config);
}

/**
 * Require Bootstrap
 */
require_once(BASE_PATH . '/app/Bootstrap.php');

/**
 * Instantiate Bootstrap
 */
new Bootstrap($config, $AutoLoader);
