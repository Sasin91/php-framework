<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 25-04-15
 * Time: 22:17
 */

namespace Core\Models;


use System\MVC\Model;

class StoreModel extends Model {

    protected $database = 'Shop';

    public function __construct(array $fillable = array())
    {
        parent::__construct($fillable);
    }
}