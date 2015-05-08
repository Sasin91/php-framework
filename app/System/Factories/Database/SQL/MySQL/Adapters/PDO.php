<?php

namespace System\Factories\Database\SQL\MySQL\Adapters;

use System\Exception\DatabaseException;

if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

class PDO
{
    /**
     * The sql query statement
     *
     * @access private
     * @var string
     */
    private $sql;

    /**
     * Binds a value to a parameter
     *
     * @access private
     * @var array
     */
    private $bind;

    /**
     * The db database object
     *
     * @access protected
     * @var object
     */
    protected $db;

    protected $_instances = array();

    public function __construct(array $config = array(), $database)
    {
        if(in_array($database, $this->_instances))
        {
            return $this->_instances[$database];
        }
        $dbhost = $config[$database]['host'];
        $dbSocket = $config[$database]['socket'];
        $dbname = $config[$database]['name'];
        $dbdsn = $config[$database]['dsn'];
        try {
            if(!isset($dbhost))
            {
                $this->db = new \PDO("$dbdsn:unix_socket=$dbSocket;dbname=$dbname", $config[$database]['user'], $config[$database]['pass']);
            } else {
                $this->db = new \PDO("$dbdsn:host=$dbhost;dbname=$dbname", $config[$database]['user'], $config[$database]['pass']);
            }


            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->db->query('SET NAMES utf8');
            $this->db->query('SET CHARACTER SET utf8');
            $this->_instances[$database] = $this->db;
        } catch(\PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    private function cleanup($bind)
    {
        if (!is_array($bind)) {
            if (!empty($bind))
                $bind = array($bind);
            else
                $bind = array();
        }
        return $bind;
    }

    /**
     * The default query handler.
     *
     * @access public
     * @param $sql (required) The SQL statement to execute.
     * @param string $bind (optional) values & parameters key/value array
     * @param string $bindMethod (optional) define which method is used to bind values
     * @param bool $asArray (optional) return an array or an object, defaults to object.
     * @return mixed
     * @throws DatabaseException
     */
    public function init(array $statement = array())
    {
        $this->sql = trim($statement['sql']);
        $this->bind = $this->cleanup($statement['bind']);
        $this->error = '';
        try {
            $stmt = $this->db->prepare($this->sql);
            if ($stmt->execute($this->bind) !== false) {
                if (preg_match("/^(" . implode("|", array("select", "describe", "pragma")) . ") /i", $this->sql)) {
                    return $stmt->fetchAll(\PDO::FETCH_OBJ);
                } elseif (preg_match("/^(" . implode("|", array("delete", "insert", "update")) . ") /i", $this->sql))
                    return $stmt->rowCount();
            }
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * Close active connection to database.
     *
     * @access public
     * @return bool Always returns true.
     */
    public function close() {
        if ( $this->db )
            $this->db = null;
        return true;
    }


    /**
     * Returns Cubrid scheme of table
     *
     * @param $sql
     * @return mixed
     */
    public function getCubrid($sql)
    {
        $clean = trim($sql);
        $stmt = $this->db->prepare($clean);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $clean);
        return $stmt->fetch();
    }

    /**
     * create a new transaction
     * @return bool
     */
    public function newTransaction()
    {
        return $this->db->beginTransaction();
    }


    /**
     * Create the queries for the transaction.
     * @var array
     */
    private $queryObjects = array();
    public function query($sql, array $values = array(), $fetch_mode)
    {
        $stmt = $this->db->prepare($sql);

        $queryObjects = array();
        $parenthesesRegexp = '#\((([^()]+|(?R))*)\)#';
        if (preg_match_all($parenthesesRegexp, $sql ,$objects)) {
            $this->queryObjects[] = $objects[1];
        }

        foreach ($values as $value) {
            $stmt->execute(array(
                    $this->buildQuery($value)
                )
            );
        }
    }

    private function buildQuery($value)
    {
        $query = array();
        foreach($this->queryObjects as $object)
        {
            if($value->$object){
                $query[] = $value->$object;
            }
        }
        return $query;
    }

    /**
     * Commit the queries
     * @return bool
     */
    public function commit()
    {
        return $this->db->commit();
    }

    /**
     * Roll back changes made
     * @return bool
     */
    public function rollback()
    {
        return $this->db->rollBack();
    }

    /**
     * Executes query and returns results.
     *
     * @access public
     * @param $sql (required) The SQL statement to execute.
     * @param $bind (optional) values & parameters key/value array
     * @param $prepare (optional) pass the query thru prepare then execute by default
     * @return mixed
     */

    public function singleQuery($sql, $bind = array(), $fetch_mode, $asObject = true, $prepare = true)
    {
        $this->error = '';
        if (!is_object($this->db)) {
            throw new DatabaseException('database is not initialized. use Factory::make("Database").', 40);
        }
        try {
            if (!empty($bind)) {
                if ($asObject !== false) {
                    return $this->init(compact('sql', 'bind', 'asArray'));
                }
                return $this->init(compact('sql', 'bind'));
            }
            if ($prepare !== false) {
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                if(!empty(strstr($sql, 'INSERT')))
                {
                    return $stmt;
                }
                if($fetch_mode == 'Column'){
                    return $stmt->fetchAll(\PDO::FETCH_COLUMN);
                } elseif($fetch_mode == 'Assoc')
                {
                    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
                } else {
                    return $stmt->fetchAll(\PDO::FETCH_OBJ);
                }
            } else {
                return $this->db->query($sql);
            }
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
}