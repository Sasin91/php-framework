<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 21-04-15
 * Time: 16:43
 */

namespace Http\Controllers;


use \app;
use Modules\Shop\Store;
use System\Authentication\Auth;
use System\Input\Input;
use System\LazyLoader;
use System\MVC\View;
use Http\Models\User;
use System\Reflector;

class Shop extends BaseController
{
    /**
     * defines which calls are legit action(s) in method.
     * @var array
     */
    protected $actions = array('index', 'add', 'remove', 'buy', 'create', 'checkout');

    protected $cart;
    protected $store;
    protected $products = array();
    protected $token;

    public function __construct(View $view)
    {
        $this->view = $view;
        $this->cart = Reflector::reflect('Modules\Shop\Cart'); // Reflect the Cart class, this is a LOT lighter & faster than creating a new instance, this even beats LazyLoading.
        $this->store = new Store(array( // set the columns which are allowed to be filled in.
            'label', 'short_description', 'description', 'price', // products & categories.
            'path', 'position', // product_pictures
            'service',
            'user_id', 'username', 'recipient', 'address', 'item', 'comments', 'qty', 'purchased' // purchased
        ));
        $this->token = User::getToken();
    }

    public function home($method, $argument)
    {
        $this->view->render('Shop/index', array(
                'title' => 'Product Categories',
                'Categories' => $this->store->allCategories())
        );
    }

    public function dance($action, Input $input)
    {
        $data = $input->arguments;
        $controller = Reflector::reflect('Http\Controllers\Shop\dance');
        $controller->cart = $this->cart;
        $controller->store = $this->store;
        $controller->token = $this->token;
        return isset($data[1]) ? $controller->$data[1]($data) : $controller->home();
    }

    protected function render($name, $action, $title, array $additional = array())
    {
        $arguments = !empty($additional) ? compact('title') + $additional : compact('title');
        if(is_null($this->view))
        {
            $this->view = LazyLoader::get('View');
        }
        $this->view->render('Shop/' . $name . '/' . $action, $arguments);
    }

}