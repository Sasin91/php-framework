<?php
/**
 * Created by PhpStorm.
 * User: JonasK
 * Date: 09-01-2015
 * Time: 22:16
 */

namespace Modules\Generators;

use Faker\Factory;

class Generator extends \System\Factories\Factory
{

    protected static $faker;

    function __construct()
    {
        parent::__construct(__DIR__);
        static::$faker = Factory::create();
    }

    /**
     * override parent method.
     * @param array $arguments
     * @return $this
     */
    public function with(array $arguments = array())
    {
        $this->arguments['with'] = $arguments;
        return $this;
    }
}