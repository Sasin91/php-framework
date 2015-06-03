<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 21-05-15
 * Time: 12:30
 */

namespace Http\Models;


class Page extends BaseModel {

    protected $table = 'pages';

    protected $hasOne = 'Http\Models\Gallery';

    public function __construct()
    {
        parent::__construct(array(
            'slug',
            'content',
            'gallery'
        ));
    }

    public function get($name)
    {
        $page = $this->select('*', $this->table)->where('slug', '=', $name)->execute();
        $gallery = $this->relations[0]->get($page[0]->gallery);
        return array_merge($page, $gallery);
    }
}