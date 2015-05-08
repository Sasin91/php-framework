<?php
include('../shared.php');
$config = \Config::get('Config');
if($config['Installed'] === false)
{
    require_once 'install.php';
    new Install($config);
}

require_once( ROOT_PATH . DS . 'Bootstrap.php');

new Bootstrap($config);
