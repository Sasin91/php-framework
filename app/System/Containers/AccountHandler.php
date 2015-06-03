<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 21-05-15
 * Time: 14:04
 */

namespace System\Containers;


use System\Authentication\Account;
use System\Traits\hasCollection;

class AccountHandler {

    use hasCollection;

    public function __construct()
    {
        $this->findOrNew('Accounts');
    }

    public function newAccount(Account $account)
    {
        $this->add($account->uid, $account);
        return $this->find($account->uid);
    }

}