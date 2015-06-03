<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 01-06-15
 * Time: 15:42
 */

namespace Http\Controllers\Shop;


use Http\Controllers\Shop;
use System\Authentication\Auth;
use System\Exception\CartException;
use System\Reflector;

abstract class ShopBase extends Shop {

    public $cart;

    public $store;

    public function add($data)
    {
        $class = Reflector::reflect(__NAMESPACE__.'\\'.$data[0]);
        $class->home();
        unset($data[0]);
        unset($data[1]);
        $this->cart->add($this->store, $data);
    }

    public function remove($data)
    {
        $class = Reflector::reflect(__NAMESPACE__.'\\'.$data[0]);
        $class->home();
        unset($data[0]);
        unset($data[1]);
        $this->cart->remove($this->store, $data);
    }

    public function checkout($data)
    {
        $action = $data[0];
        if (!empty($data['post']['token'])) {
            if (!Auth::check()) {
                return \app::redirect('users/authenticate');
            }
            if(empty($data['post']['recipient']))
            {
                $this->render($action, 'checkout', 'Checkout', array(
                    'cart' => $this->cart->items(),
                    'token' => $this->token
                ));
                return false;
            }

            if ($this->cart->checkout($this->store, $this->token, $data['post'])) {
                #$this->store->subtract('qty')->from($this->cart->items());
                var_dump($this->cart->clearCart());
                $this->render($action, 'final', 'Purchase Finished.', array('purchase' => $this->cart->items()));
                $this->cart->clearCart();
                return true;
            } else {
                $this->render($action, 'final', 'There was an issue with your purchase.', array('purchase' => $this->cart->items()));
            }
        } else {
            throw new CartException('Bad Token!');
        }
    }
}