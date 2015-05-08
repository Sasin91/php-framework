<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 09-01-15
 * Time: 12:53
 */

namespace Modules;


class Debug {

    static function dd($target)
    {
        self::prettify($target);
        exit;
    }

    /**
     * @param $data
     */
    static function prettify($data)
    {
        echo '<pre class="prettify">';
        if(is_array($data)):
            foreach($data as $piece):
                print_r($piece);
            endforeach;
        else:
            print_r($data);
        endif;
        echo '</pre>';
    }

}