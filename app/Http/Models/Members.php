<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 21-05-15
 * Time: 15:13
 */

namespace Http\Models;


class Members extends BaseModel {

    protected $table = 'members';

    public function __construct()
    {
        parent::__construct(
            array(
                'role',
                'position',
                'isInstructor',
                'teaches'
            )
        );
    }
}