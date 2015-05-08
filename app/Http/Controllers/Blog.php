<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 05-05-15
 * Time: 13:21
 */

namespace Http\Controllers;



class Blog extends BaseController {

    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new \Http\Models\Blog();
    }

    public function home($method, array $arguments = array())
    {
        return $this->view->render('Blog/index', array(
            'title' => 'Blog',
            'categories' => $this->getCategories(),
            'posts' => $this->getPosts()
        ));
    }

    public function posts()
    {

    }

    public function categories()
    {

    }

    protected function getCategories()
    {
        return $this->model->get('Categories', '*');
    }

    protected function getPosts()
    {
        return $this->model->get('Posts', '*');
    }
}