<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 01-06-15
 * Time: 15:43
 */

namespace Http\Controllers\Shop;


class dance extends ShopBase {

    public function home()
    {
        $this->render('dance', 'index', 'dance', array(
            'cart' => $this->cart->items(),
            'store' => $this->store->items(),
            'token' => $this->token
        ));
    }
}