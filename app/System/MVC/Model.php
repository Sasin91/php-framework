<?php
namespace System\MVC;

use Toolbox\ArrayTools;
use System\Containers\ActiveRecordContainer;
use System\Exception\DatabaseException;
use System\Interfaces\ActiveRecord;
use System\Reflector;

if ( ! defined('ROOT_PATH') ) exit('No direct script access allowed');


class Model extends Core implements ActiveRecord
{
    private $items;
    protected $db;
    protected $database = 'Auth';
    protected $container;
    protected $statements = array();
    protected $relations = array();
    protected $permittedAttr = array();
    protected $queryParts = array();
    private $bind = array();
    public $manyMethods = array('belongsToMany', 'hasMany');

    public function __construct(array $attributes = array())
    {
        parent::__construct();
        if(!isset($this->database))
        {
            throw new DatabaseException('please define a database in your model, like so: protected $database = "Bubblegum";.');
        }
        ActiveRecordContainer::create('ActiveRecord');
        $this->getContainer();
        $this->setDatabase($this->database);
        if(!empty($attributes))
        {
            $this->permittedAttr = $attributes;
        }
    }

    private function getContainer()
    {
        $this->container = ActiveRecordContainer::getInstances()[0];
        return true;
    }

    /**
     * Convenient way to execute a query, directly.
     * @param $sql
     * @param string $bind
     * @return mixed
     */
    public static function query($sql, $bind = '')
    {
        $instance = new static;
        return $instance->db->singleQuery($sql, $bind);
    }


    /**
     * populates a select query.
     * @param $columns
     * @param $table
     * @return static
     */
    public function select($columns, $table)
    {
        $this->populate(array('action' => 'SELECT', 'columns' => $columns, 'statement' => 'FROM', 'table' => $table));
        return $this;
    }

    /**
     * populates a update query.
     * @param $column
     * @param $table
     * @return static
     */
    public function update($column, $table)
    {
        $this->populate(array('action' => 'UPDATE', 'columns' => static::fillable($column), 'table' => $table));
        return $this;
    }

    /**
     * populates a create query.
     * @param $columns
     * @param $values
     * @param $table
     * @return mixed
     */
    public function create($columns, $values , $table)
    {
        if(is_array($values))
        {
            $values = $this->enquote($values);
        }
        $this->populate(array('action' => 'CREATE', 'columns' => '('.static::fillable($columns).')','values' => 'VALUES ('.$values.')', 'table' => $table));
        return $this->execute();
    }

    /**
     * Populates a insert query.
     * @param $columns
     * @param $values
     * @param $table
     * @return mixed
     */
    public function insert($columns, $values , $table)
    {
        if(is_array($values))
        {
            $values = $this->enquote($values);
        }
        $this->populate(array('action' => 'INSERT INTO ', 'table' => $table, 'columns' => '('.static::fillable($columns).')','values' => 'VALUES ('.$values.')'));
        return $this->execute();
    }

    /**
     * Populates a delete query.
     * @param $table
     * @return static
     */
    public function delete($table)
    {
        $this->populate(array('action' => 'DELETE', 'statement' => 'FROM ', 'table' => $table));
        return $this;
    }

    /**
     * returns an array of fields(columns) in table.
     * @param $table
     * @return mixed
     */
    public function describe($table)
    {
        $this->populate(array('action' => 'DESCRIBE ', 'table' => $table));
        return $this->execute('Column');
    }

    /**
     * defines a limit of only one row returned from database.
     * @return $this
     */
    public function first()
    {
        $this->populate(array('request' => 'LIMIT', 'num' => 1));
        return $this;
    }

    /**
     * Populates where clauses for the query.
     * @param array $clauses
     * @return $this
     */
    public function where(array $clauses = array())
    {
        $operator = in_array('operator', $clauses) ? $clauses['operator'] : '=';
        $joiner = in_array('joiner', $clauses) ? $clauses['joiner'] : null;
        unset($clauses['joiner']);
        unset($clauses['operator']);
        foreach($clauses as $column => $value)
        {
            $clause = 'WHERE ';
            $col = preg_replace('/:/', '', $column);
            $val = '"'.$value.'"';
            $statement =  compact('clause','col','operator', 'val', 'joiner');
            $this->bind[] = array_filter(array($column => $value));
            $this->populate($statement, 'where');
        }
        return $this;
    }

    /**
     * Implode and add quotes
     * @param array $array
     * @return string
     */
    private function enquote(array $array = array())
    {
        return '"'.implode('", "', $array).'"';

    }


    /**
     * Finishes the method chain and executes the query against the database.
     * @param string $fetch_mode
     * @return mixed
     */
    public function execute($fetch_mode = 'object')
    {
        return $this->BuildQuery($fetch_mode);
    }

    /**
     * Builds the query.
     * @return array
     */
    private function BuildQuery($fetch_mode)
    {
        $toClean = array('UPDATE ', 'INSERT INTO ', 'CREATE ', 'DESCRIBE ');
        if(!empty($this->queryParts))
        {
            $success = array();
            for($i = 0; $i < count($this->queryParts); $i++) {
                if (!empty($this->queryParts['where'])) {
                    $query = array_merge($this->queryParts[$i], $this->queryParts['where']);
                } else {
                    $query = $this->queryParts[$i];
                }
                if (in_array($query['action'], $toClean)) {
                    unset($query['statement']);
                    unset($query['clause']);
                    unset($query['col']);
                    unset($query['operator']);
                    unset($query['val']);
                }
                $query = (trim(ArrayTools::array2string(array_unique($query), ' ')));
                if (!empty($query)) {
                    $success[] = $this->db->singleQuery($query, $this->bind, $fetch_mode);
                }
            }

            return $success[0];
        }
        return false;
    }

    /**
     * Populates the query to be.
     * @param array $data
     * @return array
     */
    private function populate(array $data = array(), $key = '')
    {
        if(!empty($key))
            return $this->queryParts[$key] = $data;
        return $this->queryParts[] = $data;
    }

    /**
     * verifies the presence of keys in $permittedAttr
     * @param $attributes
     * @return null|string
     */
    protected function fillable($attributes)
    {
        if(!is_array($attributes))
        {
            if(strpos($attributes, ', '))
            {
                $array = array_unique(!empty($this->permittedAttr) ? ArrayTools::arrayMatch(explode(', ', $attributes), $this->permittedAttr) : false);
            } else {
                $array = in_array($attributes, $this->permittedAttr);
            }
        } else {
            $array = array_unique(!empty($this->permittedAttr) ? ArrayTools::arrayMatch($attributes, $this->permittedAttr) : false);
        }
        if(!empty($array))
            return rtrim(ArrayTools::array2string($array, ', '), ', ');
        return null;
    }

    public function setDatabase($database)
    {
        $this->db = $this->app->newDatabase($database);
        return !is_null($this->db) ? $this->db : $this->setDatabase($database);
    }

    public function hasRelationship($parent, $object)
    {
        if(!is_object($this->container))
        {
            $this->createContainer();
        }
        $exists = $this->container->exists($object) ? true : false;
        if($exists)
            if(in_array($parent, $this->relations))
                foreach ($this->relations as $k => $v) {
                    $this->items[] = $v;
                }
        return $this->items;
    }

    private $classes;
    public function hasOne($class)
    {
        $this->noContainer();
        $parent = array_pop(explode('\\', $this->calling_class()));
        $collection = array();
        if(empty($class))
            return $this->container->get($parent);

        $object = $this->container->get($parent);
            if(array_pop(explode('\\', $object)) == $class)
            {
                $collection[] = Reflector::reflect($object);
            }
        return $collection;
    }

    public function belongsToOne($object)
    {
        $this->noContainer();
        $class = $this->calling_class();
        $this->container->set($object, array(array_pop(explode('\\', $class)) => $class));
    }

    public function belongsToMany(array $objects)
    {
        $parent = $this->calling_class();
        foreach ($objects as $object) {
            if(!$this->hasRelationship($parent, $object))
                $this->classes[] = $object;
        }
    }

    public function hasMany(array $classes = array())
    {
        $this->NoContainer();
        $parent = array_pop(explode('\\', $this->calling_class()));
        $collection = array();
        if(empty($classes))
        {
            return $this->container->get($parent);
        }

        foreach ($this->container->get($parent) as $object) {
            foreach($classes as $class) {
                $label = array_pop(explode('\\', $object));
                if ($label == $class) {
                    $collection[$label] = Reflector::reflect($object);
                }
            }
        }
        return $collection;
    }

    public function find($id, $columns = array('*'))
    {
        if (is_array($id) && empty($id)) return $this->newCollection($id);

        return $this->find($id, $columns);
    }

    public function newCollection($name ,array $models = array())
    {
        $collection = new ActiveRecordContainer();
        $collection->set($name, $models);
        return $collection;
    }

    public function hasTable($object)
    {
        return $this->db->object($object);
    }

    private function calling_class() {

        //get the trace
        $trace = debug_backtrace();

        // Get the class that is asking for who awoke it
        $class = $trace[1]['class'];

        // +1 to i cos we have to account for calling this function
        for ( $i=1; $i<count( $trace ); $i++ ) {
            if ( isset( $trace[$i] ) ) // is it set?
                if ( $class != $trace[$i]['class'] ) // is it a different class
                    return $trace[$i]['class'];
        }
        return null;
    }

    private function NoContainer()
    {
        return is_null($this->container) ? $this->getContainer() : false;
    }


}