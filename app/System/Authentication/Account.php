<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 21-05-15
 * Time: 13:58
 */

namespace System\Authentication;


class Account {

    public $token;

    public $authenticated;

    public $isInstructor;

    public $id;

    public $uid;

    public $role;

    public $type;

    public $email;

    public $username;

    public $ip;

    public $image;

    public $member_since;

    public $info;

    public $teaches = array();


    public function __construct($account)
    {
        extract((array)$account);

        $this->isInstructor = $isInstructor === 0 ? true : false;
        $this->id = $id;
        $this->uid = $uid;
        $this->role = $role;
        $this->type = $type;
        $this->email = $email;
        $this->username = $label;
        $this->ip = $ip;
        $this->image = $image;
        $this->member_since = $joindate;
        $this->info = $info;

        if($this->isInstructor)
            $this->teaches = explode(', ', $teaches);

        return $this;
    }
}