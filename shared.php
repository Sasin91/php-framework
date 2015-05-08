<?php
const DS = DIRECTORY_SEPARATOR; // Lets defined DIRECTORY_SEPARATOR as DS, for convenience.
const ROOT_PATH = '../app'; // Because we actually load index.php in public when visiting a site hosted by our framework, we'll need to tell that our app is in fact one step back.
const BASE_PATH = __DIR__;
/**
 * Require and instantiate our Autoloader
 */
require( __DIR__ . DS . 'app/Autoloader.php');
new Autoloader('',__DIR__. DS . 'app');

