<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 01-06-15
 * Time: 23:14
 */

namespace Modules\Dashboard\Admin\Models;
use Modules\Dashboard\Admin\Models\Shop;

/**
 * Intended for easy expansion for future additions
 * Class ChainMethod_ShopModel
 * @package Modules\Dashboard\Admin\Models
 */
abstract class ChainMethodShop extends Shop {

    protected $method;

    protected $where = false;

    protected $columns;

    protected $column;

    protected $operator;

    protected $value;


    public static function get($columns = '*')
    {
        $instance = new static;
        $instance->columns = $columns;
        $instance->method = 'select';
        return $instance;
    }

    public static function change($columns)
    {
        $instance = new static;
        $instance->columns = $columns;
        $instance->method = 'update';
        return $instance;
    }

    public static function remove()
    {
        $instance = new static;
        $instance->method = 'delete';
        return $instance;
    }

    public function withWhereClause()
    {
        $this->where = true;
        return $this;
    }

    public function column($column)
    {
        $this->column = $column;
        return $this;
    }

    public function equals()
    {
        $this->operator = '=';
        return $this;
    }

    public function like()
    {
        $this->operator = 'LIKE';
        return $this;
    }

    public function isNot()
    {
        $this->operator = 'IS NOT';
        return $this;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function finish()
    {
        extract(get_object_vars($this));

        if(!$this->where)
        {
            return $this->$method($columns, $this->table)->respondWith('Array')->execute();
        }
        return $this->$method($columns, $this->table)->where($column, $operator, $value)->respondWith('Array')->execute();
    }
}