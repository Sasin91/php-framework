<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 26-05-15
 * Time: 11:12
 */

namespace Modules\Dashboard;


class FormatQuery {

    public static function update(array $requests)
    {
        $query = '';
        foreach ($requests as $column => $value) {
            if(sizeof(array_keys($requests)) > 1)
            {
                $query .= $column . '=' . '"' .$value . '"' . ', ';
            } else
            {
                $query .= $column . '=' . '"' . $value . '"' .'';
            }
        }
        return $query;
    }

    public static function insert(array $requests)
    {
        $columns = '';
        $values = '';
        $data = array();
        foreach ($requests as $column => $value) {
            $columns .= $column . ', ';
            $values .= $value . ', ';
            $data['columns'] = explode(', ', $columns);
            $data['values'] = explode(', ', $values);
            array_pop($data['values']);
            array_pop($data['columns']);
        }
        return $data;
    }

}