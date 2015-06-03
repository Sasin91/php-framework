<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 25-05-2015
 * Time: 03:19
 */

namespace Modules\Dashboard\Admin\Models;


use Modules\Dashboard\BaseModel;

class Galleries extends BaseModel {

    public function __construct()
    {
        parent::__construct(array(
            'images',
            'description'
        ));
    }

}