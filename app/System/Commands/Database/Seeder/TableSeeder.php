<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 07-04-15
 * Time: 08:50
 */

namespace System\Commands\Database\Seeder;

use Config;
use Faker\Factory;
use System\MVC\Model;
use Toolbox\ArrayTools;
use Toolbox\StringTools;

class TableSeeder extends Model
{

    protected $table = '';
    protected $columns = array();

    public function __construct($database)
    {
        $this->database = StringTools::CapitalizeFirst($database);
        parent::__construct();
        return $this;
    }


    protected $faker;
    private $num;

    public function seed($table, $num = '10')
    {
        $this->table = $table;
        array_walk_recursive(static::describe($table), function ($value, $key) {
            if ($key == 'Field')
                $this->columns[] = $value;
        });
        static::$permittedAttr = $this->columns;
        $this->num = $num;
        $this->faker = Factory::create(Config::get()->file('Base')['language']);
        var_dump(!empty(static::insert($this->columns, array_unique($this->compileTable()), $this->table)) ? true : false);
    }

    private $faker_types = array();

    private function compileTable()
    {
        $data = array();
        $this->faker_types = array(
            'gallery' => array('imageUrl', 'dateTime'),
            'person' => array('name', 'email'),
            'merchandise' => array('title', 'ISBN', 'randomNumber'),
            'post' => array('title', 'text', 'slug'),
            'post_with_images' => array('title', 'text', 'imageUrl', 'slug')
        );

        foreach ($this->faker_types as $k => $v) {
            if ($this->verifyPresenceOf($v))
                $data[$k] = $v;
        }
        return $this->generate($this->getMatchingFakerMethods($data));


    }

    private function verifyPresenceOf($column)
    {
        $result = array();
        if (ArrayTools::isMultidimensional($column)) {

            foreach ($column as $value) {
                $result[] = ArrayTools::arrayContains($value, (array)static::$permittedAttr);
            }
        } else {
            $result[] = ArrayTools::arrayContains($column, (array)static::$permittedAttr);
        }
        return $result;
    }

    private function getMatchingFakerMethods(array $data = array())
    {
        $matches = array();
        $keys = array_values($data);
        $frameKeys = array_values($this->columns);
        foreach ($frameKeys as $key) {
            $matches[] = ArrayTools::arrayContains($keys, $key);
        }
        return $matches;
    }

    private function generate($matchingKeys)
    {
        $data = array();
        $random = ArrayTools::getRandom($matchingKeys);
        for ($i = 0; $i < $this->num; $i++) {
            $data[] = is_string($random[$i]) ? $this->faker->$random[$i]() : null; // Looks prettier this way :p
        }
        return $data;
    }
}