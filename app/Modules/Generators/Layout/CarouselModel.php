<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 20-01-15
 * Time: 13:54
 */

namespace Core\Models\Layout;


use System\MVC\Model;

class CarouselModel extends Model
{

    protected $database = 'Layout';

    public function __construct()
    {
        parent::__construct();
    }

    public static function getImageBy(array $clauses = array())
    {
        return static::query('SELECT image FROM carousel WHERE page = :page', $clauses);
    }

    public static function getTextBy(array $clauses = array())
    {
        return static::query('SELECT text, btn_link, btn_label FROM carousel WHERE page = :page', $clauses);
    }


}