<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 25-05-2015
 * Time: 18:23
 */

namespace Modules\Dashboard;


use System\MVC\View;

class Home {

    public function __construct(View $view, $level, $user, $url)
    {
        $view->render('Dashboard/'.$level.'/' . $url['1'] . '', array('title' => $level.' Panel', 'profile' => $user));
    }
}