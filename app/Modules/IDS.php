<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 22-04-15
 * Time: 15:57
 */
namespace Modules;
use IDS\Init;
use IDS\Monitor;

/**
 * Itrusion Detection System. Not an essential part of the system and does use a class i haven't written.
 * This takes note of attempted intrusions, eg. SQL injection and writes a log file containing details.
 * Class IDS
 */
class IDS {

    protected $request = array();
    public function __construct()
    {
        $this->request[] = array(
            'REQUEST' => $_REQUEST,
            'GET' => $_GET,
            'POST' => $_POST,
            'COOKIE' => $_COOKIE,
            'URI' => $_SERVER['REQUEST_URI'],
            'AGENT' => $_SERVER['HTTP_USER_AGENT']
        );

        $ids_init = Init::init(\Config::get('IDS.ini'));
        $ids = new Monitor($ids_init);
        $ids_result = $ids->run($this->request);
        if(!$ids_result->isEmpty()) {
            if (is_writeable(ROOT_PATH . DS  . '/Storage/Logs/intrusionAttempt.log')) {
                file_put_contents(ROOT_PATH . DS . '/Storage/Logs/intrusionAttempt.log', $ids_result);
                return true;
            } else {
            throw new \System\Exception\FilesystemException('Storage/Logs/ is NOT writeable.');
            }
        }
    }
}