<?php

namespace Http\Models;

use System\MVC\Model;

if (!defined('ROOT_PATH')) exit('No direct script access allowed');


Abstract class BaseModel extends Model // BaseModel's goal is the same as BaseController.
{
    private $data;

    function __construct()
    {
        parent::__construct();
    }

    function search($data)
    {
        $encoded = base64_encode($data);
        $key = 'Search' . $encoded;
        if ($this->isInCache($key)) {
            return $this->cache->get($key); //@TODO: NEEDS UPDATE !!
        }
        $this->data = array(
            'Auth' => array(),
            'Layout' => array(),
            'Gallery' => array(),
            'Forum' => array()
        );
        $bind = [':query' => $data];
        $this->data['Auth']['users'] = $this->app->db('Auth')->select('id, username, email', 'users', 'id LIKE :query OR username LIKE :query OR email LIKE :query', '', $bind);
        $this->data['Layout']['content'] = $this->app->db('Layout')->select('label, part', 'content', 'label LIKE :query OR part LIKE :query OR content LIKE :query', '', $bind);
        $this->data['Layout']['pages'] = $this->app->db('Layout')->select('title', 'pages', 'title LIKE :query', '', $bind);
        $this->data['Gallery']['collection'] = $this->app->db('Gallery')->select('label', 'collection', 'label LIKE :query', '', $bind);
        $this->data['Gallery']['images'] = $this->app->db('Gallery')->select('label', 'images', 'label LIKE :query', '', $bind);
        $this->data['Forum']['categories'] = $this->app->db('Forum')->select('slug, name', 'categories', 'slug LIKE :query OR name LIKE :query', '', $bind);
        $this->data['Forum']['posts'] = $this->app->db('Forum')->select('slug, tags, title', 'posts', 'slug LIKE :query OR tags LIKE :query OR title LIKE :query', '', $bind);
        $this->cache->set($key, $this->data);
        return $this->data;
    }
}