<?php
namespace System\MVC;

use System\Exception\DatabaseException;
use System\Interfaces\ActiveRecord;
use System\Traits\hasInstances;
use Toolbox\ArrayTools;

if (!defined('ROOT_PATH')) exit('No direct script access allowed');


class Model extends Core implements ActiveRecord
{
    /**
     * Database object
     * @var
     */
    protected $db;

    /**
     * Database name
     * @var string
     */
    protected $database = '';

    /**
     * Database table
     * @var string
     */
    protected $table = 'users';

    /**
     * If true, response (output) will be in Json format.
     * @var bool
     */
    protected $respondWithJson = false;

    /**
     * Return query as:
     * @var string
     */
    protected $fetch_type = 'object';

    /**
     * Query statements
     * @var array
     */
    protected $statements = array();

    /**
     * Defines which Columns are accessible in database.
     * @var array
     */
    protected $permittedAttr = array();

    /**
     * Parts of the query in process.
     * @var array
     */
    protected $queryParts = array();

    /**
     * Instance of class System\MVC\Core
     * @object
     */
    protected $core;

    // ----------------------
    # Active Record relations

    /**
     * Belongs To
     * @var
     */
    protected $belongsTo;

    /**
     * Belongs To Many
     * @var
     */
    protected $belongsToMany;

    /**
     * Has One
     * @var
     */
    protected $hasOne;

    /**
     * Has Many
     * @var
     */
    protected $hasMany;

    /**
     * Active Record Methods
     * @var array
     */
    public $relationshipMethods = array(
        'belongsToMany', 'hasMany',
        'belongsToOne', 'hasOne'
    );

     # End Active Record
    // -------------------

    /**
     * Bind variables.
     * @var array
     */
    private $bind = array();

    use hasInstances, \System\Traits\ActiveRecord;

    /**
     * @param array $attributes
     * @throws DatabaseException
     */
    public function __construct(array $attributes = array())
    {
        if (empty($this->database)) {
            $this->database = array_keys(Core::getConfig()['Database']['Factory']['Databases'])[0];
        }
        $this->setDatabase($this->database);
        $this->setTable($this->table);
        $this->parseRelations();
        $this->getCore();
        $this->_saveInstance($this);
        if (!empty($attributes)) {
            $this->permittedAttr = $attributes;
        }
    }

    /**
     * Yes, it is intended to return $table on both.
     * @param string $table
     * @return string
     */
    public function setTable($table = '')
    {
        if (empty($table) OR $this->table === $table) {
            return $table;
        }
        return $this->table = strtolower($table);
    }

    public function parseRelations()
    {
        foreach ($this->relationshipMethods as $method) {
            if(isset($this->$method))
            {
                switch($method)
                {
                    case 'hasOne':
                       $this->relations = $this->hasOne($this->$method);
                    break;

                    case 'hasMany':
                        $this->relations = $this->hasMany($this->$method);
                    break;

                    case 'belongsToOne':
                        $this->belongsToOne($this->$method);
                    break;

                    case 'belongsToMany':
                        $this->belongsToMany($this->$method);
                    break;

                }
            }
        }
    }

    /**
     * Sets Core instance
     */
    private function getCore()
    {
        $this->core = Core::$instance;
    }

    public function newTransaction()
    {
        return $this->db->newTransaction();
    }

    public function endTransaction()
    {
        return $this->db->commit();
    }

    public function rollback()
    {
        return $this->db->rollback();
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
     * @param $id
     * @param string $column
     * @return mixed
     */
    public static function find($id, $column = 'id', $table = '')
    {
        $model = self::_getInstance();
        if(!empty($table))
        {
            $model->table = $table;
        }
        return $model->select('*', $model->table)->where($column, '=', $id)->execute();
    }

    /**
     * Returns request or throws DatabaseException
     * @param $id
     * @param string $column
     * @return mixed
     * @throws DatabaseException
     */
    public static function findOrFail($id, $column = 'id')
    {
        $model = self::_getInstance();
        $request = $model->find($id, $column);
        if(empty($request))
            throw new DatabaseException('Failed to find '.$id.' in'.$model->table.' on column '.$column);
        return $request;
    }

    /**
     * populates a select query.
     * @param $columns
     * @param $table
     * @return static
     */
    public function select($columns, $table = '')
    {
        @$this->populate(array('action' => 'SELECT', 'columns' => $columns, 'statement' => 'FROM', 'table' => $this->setTable($table)));
        return $this;
    }

    /**
     * populates a update query.
     * @param $column
     * @param $table
     * @return static
     */
    public function update($requests, $table = '')
    {
        if(is_array($requests))
        {
            $str = '';
            foreach ($requests as $k => $v) {
                $str .= $k.'='.'"'.$v.'"'.', ';
            }
            $request = chop($str, ', ');

        } else {
            $request = $requests;
        }
        @$this->populate(array('action' => 'UPDATE', 'table' => $this->setTable($table), 'values' => 'SET ' .$request. ''));
        return $this;
    }

    /**
     * populates a create query.
     * @param $columns
     * @param $values
     * @param $table
     * @return mixed
     */
    public function create($columns, $values, $table = '')
    {
        if (is_array($values)) {
            $values = $this->enquote($values);
        }
        @$this->populate(array('action' => 'CREATE', 'columns' => '(' . static::fillable($columns) . ')', 'values' => 'VALUES (' . $values . ')', 'table' => $this->setTable($table)));
        return $this->execute();
    }

    /**
     * Populates a insert query.
     * @param $columns
     * @param $values
     * @param $table
     * @return mixed
     */
    public function insert($columns, $values, $table = '')
    {
        if (is_array($values)) {
            $values = $this->enquote($values);
        }
        @$this->populate(array('action' => 'INSERT INTO ', 'table' => $this->setTable($table), 'columns' => '(' . static::fillable($columns) . ')', 'values' => 'VALUES (' . $values . ')'));
        return $this->execute();
    }

    /**
     * Populates a delete query.
     * @param $table
     * @return static
     */
    public function delete($table = '')
    {
        @$this->populate(array('action' => 'DELETE', 'statement' => 'FROM ', 'table' => $this->setTable($table)));
        return $this;
    }

    /**
     * returns an array of fields(columns) in table.
     * @param $table
     * @return mixed
     */
    public function describe($table = '')
    {
        if(empty($table))
        {
            $table = $this->table;
        }
        @$this->populate(array('action' => 'DESCRIBE ', 'table' => $this->setTable($table)));
        $this->fetch_type = 'Column';
        return $this->execute();
    }

    /**
     * defines a limit of only one row returned from database.
     * @return $this
     */
    public function first()
    {
        @$this->populate(array('request' => 'LIMIT', 'num' => 1));
        return $this;
    }

    /**
     * Populates where clauses for the query
     * @param $column
     * @param $operator
     * @param $value
     * @param $join
     */
    public function where($column, $operator, $value, $joiner = '')
    {
        $clause = 'WHERE ';
        $col = preg_replace('/:/', '', $column);
        $val = '"' . $value . '"';
        $statement = compact('clause', 'col', 'operator', 'val', 'joiner');
        $this->bind[] = array_filter(array($column => $value));
        @$this->populate($statement, 'where');
        return $this;
    }

    /**
     * Populates where clauses for the query.
     * @param array $clauses
     * @return $this
     */
    public function whereArray(array $clauses = array())
    {
        $operator = in_array('operator', $clauses) ? $clauses['operator'] : '=';
        $joiner = in_array('joiner', $clauses) ? $clauses['joiner'] : null;
        unset($clauses['joiner']);
        unset($clauses['operator']);
        foreach ($clauses as $column => $value) {
            $clause = 'WHERE ';
            $col = preg_replace('/:/', '', $column);
            $val = '"' . $value . '"';
            $statement = compact('clause', 'col', 'operator', 'val', 'joiner');
            $this->bind[] = array_filter(array($column => $value));
            @$this->populate($statement, 'where');
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
        return '"' . implode('", "', $array) . '"';

    }

    /**
     * Sets JSON response variable
     * @return $this
     */
    public function asJson()
    {
        $this->respondWithJson = true;
        return $this;
    }

    /**
     * Sets fetch_type
     * @param string $type
     * @return $this
     */
    public function respondWith($type = 'object')
    {
        $this->fetch_type = $type;
        return $this;
    }

    /**
     * Finishes the method chain and executes the query against the database.
     * @return mixed
     */
    public function execute()
    {
        if(is_null($this->db))
        {
            $this->setDatabase($this->database);
        }
        return $this->BuildQuery($this->fetch_type);
    }

    /**
     * Builds the query.
     * @return array
     */
    private function BuildQuery($fetch_mode)
    {
        $toClean = array('UPDATE ', 'INSERT INTO ', 'CREATE ', 'DESCRIBE ');
        if (!empty($this->queryParts)) {
            $success = array();
            for ($i = 0; $i < count($this->queryParts); $i++) {
                if (!empty($this->queryParts['where'])) {
                    if(!empty($this->queryParts[$i]))
                    {
                        $query = array_merge($this->queryParts[$i], $this->queryParts['where']);
                    }
                } else {
                    $query = $this->queryParts[$i];
                }
                /**
                 * If needed, remove unused keys
                 */
                if(isset($query['action'])) {
                    if (in_array($query['action'], $toClean)) {
                        unset($query['statement']);
                        unset($query['clause']);
                        unset($query['col']);
                        unset($query['operator']);
                        unset($query['val']);
                    }
                }
                $query = is_array($query) ? trim(ArrayTools::array2string(array_unique($query), ' ')) : trim($query);
                if (!empty($query)) {
                    $success[] = $this->db->singleQuery($query, $this->bind, $fetch_mode);
                }
            }
            return $this->respondWithJson ? json_encode($success[0]) : $success[0];
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
        if (!empty($key))
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
        if (!is_array($attributes)) {
            if (strpos($attributes, ', ')) {
                $array = array_unique(!empty($this->permittedAttr) ? ArrayTools::arrayMatch(explode(', ', $attributes), $this->permittedAttr) : false);
            } else {
                $array = in_array($attributes, $this->permittedAttr);
            }
        } else {
            $array = array_unique(!empty($this->permittedAttr) ? ArrayTools::arrayMatch($attributes, $this->permittedAttr) : false);
        }
        if (!empty($array))
            return rtrim(ArrayTools::array2string($array, ', '), ', ');
        return null;
    }

    public function setDatabase($database)
    {
        $database = strtolower($database);

        if(is_null($this->core))
        {
            $this->getCore();
        }
        $this->db = $this->core->app->newDatabase($database);
        return !is_null($this->db) ? $this->db : $this->setDatabase($database);
    }

    public function hasTable($object)
    {
        return $this->db->object($object);
    }
}