<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 25-05-2015
 * Time: 03:19
 */

namespace Modules\Dashboard\Admin\Models;


use Modules\Dashboard\BaseModel;

class Pages extends BaseModel {

    protected $table = 'pages';

    public function __construct()
    {
        parent::__construct(array(
            'slug',
            'content',
            'gallery'
        ));
    }
}