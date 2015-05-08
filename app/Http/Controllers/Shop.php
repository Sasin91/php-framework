<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 21-04-15
 * Time: 16:43
 */

namespace Http\Controllers;


use Http\Models\User;
use Core\app;
use Core\Http\Cart\Store;
use Core\Http\Toolbox\ArrayTools;
use System\Reflector;

class Shop extends BaseController {


    /**
     * defines which methods are permitted calls.
     * @var array
     */
    protected $methods = array('family', 'upcycles');

    /**
     * defines which calls are legit action(s) in method.
     * @var array
     */
    protected $actions = array('index', 'add', 'remove', 'buy', 'create', 'checkout');

    protected $cart;
    protected $store;
    protected $products = array();
    private $token;

    public function __construct()
    {
        parent::__construct();
        $this->cart = Reflector::reflect('Core\Http\Cart\Cart'); // Reflect the Cart class, this is a LOT lighter & faster than creating a new instance, this even beats LazyLoading.
        $this->store = new Store(array( // set the columns which are allowed to be filled in.
            'label', 'short_description', 'description', 'price', // products & categories.
            'path', 'position', // product_pictures
            'service',
            'user_id','username', 'recipient', 'address', 'item', 'comments', 'qty', 'purchased' // purchased
        ));
        $this->token = User::getToken();
    }

    public function home($method, array $argument = array())
    {
        return $this->view->render('Shop/index', array(
            'title' => 'Product Categories',
            'Categories' => $this->store->categories())
        );
    }

    /**
     * Our home.
     * Look Http\Controllers\Users for a different approach to set up a class.
     * @param array $arguments
     * @param $method
     * @return bool
     */
    public function __call($method, array $arguments = array())
    {
        $method = ArrayTools::getFirstIn(ArrayTools::arrayMatch($this->methods, $arguments[1]));
        $action = isset($arguments[1][1]) ? ArrayTools::getFirstIn(ArrayTools::arrayMatch($this->actions, $arguments[1])) : 'index';
        if($action == 'checkout')
            {
               return $this->checkout($method, $arguments[0], $arguments[1]);
            }
                $this->$method($action, $arguments);
    }

    private function family($action, array $arguments = array())
    {
        $this->cart->$action($this->store, $arguments);
        return $this->render(__FUNCTION__, 'index', 'family', array(
            'cart' => $this->cart->items(),
            'store' => $this->store->items(),
            'token' => $this->token
        ));
    }

    private function upcycles($action, array $arguments = array())
    {
        $this->cart->$action($this->store, $arguments);
        return $this->render(__FUNCTION__, 'index', 'upcycles', array(
            'cart' => $this->cart->items(),
            'store' => $this->store->items(),
            'token' => $this->token
        ));
    }


    private function checkout($action, $request_method, array $arguments = array())
    {
        if($request_method == 'POST' && !empty($arguments['post']['token']))
        {
            if(!User::Authenticate())
            {
                return app::redirect('users/authenticate');
            }
            if($this->cart->checkout($this->store, $this->token, $arguments))
            {
                #$this->store->subtract('qty')->from($this->cart->items());
                $this->render($action, 'final', 'Purchase Finished.', array('purchase' => $this->cart->items()));
                $this->cart->clearCart();
                return true;
            } else {
                return $this->render($action, 'final', 'There was an issue with your purchase.', array('purchase' => $this->cart->items()));
            }
        } else {
            return $this->render($action, 'checkout', 'Checkout', array(
                'cart' => $this->cart->items(),
                'token' => $this->token
            ));
        }
    }

    private function render($name, $action, $title, array $additional = array())
    {
        $arguments = !empty($additional) ? compact('title')+$additional : compact('title');
        return $this->view->render('Shop/'.$name.'/'.$action, $arguments);
    }

}