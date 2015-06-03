<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 23-03-15
 * Time: 14:08
 */

namespace System\Factories\Database;


use System\Factories\Database\SQL\MySQL\Adapters\PDO;

class Database
{

    protected $database;
    protected $options = array();
    private $db;

    public function __construct($options = array())
    {
        $this->options = $options;
        return $this;
    }

    public function make(array $arguments = array())
    {
        $this->db = $arguments[0]['database'];
        return $this->$arguments[0]['config']['class']($arguments[0]['config']);
    }


    public function PDO(array $config = array())
    {
        return new PDO($config['Databases'], $this->db);
    }

    public function destroy()
    {
        $this->database = NULL;
    }
}