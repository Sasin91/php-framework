<?php

namespace Http\Controllers;


use Http\Models\Page;

class Home extends BaseController
{
    public function home($action, $arguments)
    {
        $page = new Page();
        $content = array(
            'title' => 'Home',
            'content' => $page->get(__FUNCTION__)
        );
        $this->view->render('Home/index', $content);
    }
}