<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 18-03-15
 * Time: 18:49
 */

namespace System\Commands\MVC;


class ViewCommand
{

    /**
     * @param array $options
     */
    public function build(array $options)
    {
        $code = '<div class="container">';
        file_put_contents(ROOT_PATH . DS . 'Application/View' . DS . $options['name'] . '.php', $code);
    }
}