<?php
namespace Core\Application\Cart;


use Core\app;
use Toolbox\ArrayTools;
use System\Authentication\Session;
use System\Exception\CartException;
use System\Models\Auth;

/**
 * Please do note the use of non-default calls that are items in my framework.
 * Class Cart
 * @package Core\Application\Cart
 */
class Cart implements \Countable {

    /**
     * store the items.
     * @var array
     */
    protected $items = array();

    /**
     * for tracking iterations.
     * @var int
     */
    protected $position = 0;

    /**
     * Store the ids, for iterations.
     * @var array
     */
    protected $ids = array();

    /**
     * Indicates if cart is empty.
     * @return bool
     */
    public function isEmpty() {
        return (empty($this->items));
    }

    public function count()
    {
        return count($this->items);
    }

    public function index(){}

    /**
     * Add item(s) to cart.
     * @param Item $model
     * @param array $item
     * @throws CartException
     */
    public function add(Store $store, array $items = array())
    {
        if(!$store instanceof Store) { throw new CartException('Invalid class given for Model.'); } // Fix for security issue where imposing clone class was injected.
        $items = ArrayTools::sanitize($items); // Sanitize the array.
        array_shift($items[1]);
        array_shift($items[1]);
        $auth = Auth::what('user'); // Get the Auth object for user.
        /**
         * using session for cart instead of a dedicated Reflected class comes with a *slight* overhead.
         * however that is how i understood this task is to be solved, in class.
         *
         * shorthand for verifying that user has session and extracting mail if so.
         */
        $userMail = $auth->has('authenticated')->session() ? $auth->get('email')->session() : null;
        if(is_null($userMail))
        {
            return app::redirect('users/authenticate');
        }

        $cart = Session::get('user')['cart']; // get the cart or return empty.
        foreach ($items[1] as $key => $value) { // loop the array of items.
            $item = $store->getItem($value);

            if (ArrayTools::existInObjectArray($item->label, $cart[$value])) { // Verify the existence of every item, if true, add +1 else create new.
                $this->update($store, $item, $cart[$value]->qty+1);
            } else {
                if(!empty($item)) {
                    $item->qty = 1;
                    Session::set('user', array('cart' => array($item->label => $item))); // create the cart in Session, using direct session class call to reduce a little overhead.
                    $this->ids[] = $item->id; // Add the id to Ids[], for Iteration.
                }
            }

        } // If the array of Items is empty, return the cart.
        return $cart;
    }

    /**
     * Update item already in cart.
     * @param $item
     * @param $qty
     */
    private function update(Store $model, $item, $qty)
    {
        $id = $item->id;

        // Delete or update accordingly
        if ($qty === 0) {
            $this->remove($model, $item);
        } elseif ( ($qty > 0) && ($qty != $item->qty)) {
            if(Session::get('user')['cart'][$item->label]->id === $id)
            {
                $item->qty = $qty;
                Session::set('user', array('cart' => array($item->label => $item)));
            }
        }

    }

    /**
     *  Delete item already in cart.
     * @param $item
     */
    public function remove(Store $model, $item)
    {

        $item = array_pop($item[1]);
        $cart = Session::get('user')['cart'];
        // Remove it:
        if(is_object($item))
        {
            $presence = $cart->$item->label;
        } else {
            $presence = $cart[$item];
        }
        if(!empty($presence))
        {
            Session::remove('user', array('cart' => array($item)));

            // Remove the stored id, too:
            $index = array_search($item['id'], $this->ids);
            unset($this->ids[$index]);

            // Recreate that array to prevent holes:
            $this->ids = array_values($this->ids);

        }
    }

    /**
     * Finalize the purchase.
     * @param Item $model
     * @param array $token
     * @return bool
     */
    private $model;
    private $auth;
    private $success = array();
    private $arguments = array();
    public function checkout(Store $store, $token, $arguments)
    {
        $this->auth = Auth::what('user');
        if(!empty($token[0]) && !empty($this->auth->get('token')->session())) { // Not completely optimal..

            $this->arguments = $arguments['post'];
            $this->model = $store->model;

            $inCart = $this->items();
            if (!empty($inCart)) {
                array_walk_recursive($inCart, function ($item, $key) {
                    $checkout['user_id'] = $this->auth->get('id')->session();
                    $checkout['username'] = $this->auth->get('label')->session();
                    $checkout['address'] = $this->arguments['address'];
                    $checkout['recipient'] = $this->arguments['recipient'];
                    $checkout['item'] = $item->label;
                    $checkout['comments'] = $this->arguments['comments'];
                    $checkout['qty'] = (string)$item->qty;
                    $checkout['purchased'] = date('y-m-d');
                    $this->success[] = $this->model->insert('user_id, username, recipient, address, item, comments, qty, purchased', (array)$checkout, 'purchased');
                });
                if (!empty($this->success)) {
                    Session::set('feedback_positive', 'Køb udført. Du vil modtage deres varer indenfor 1-2 ugers tid.', true);
                    return true;
                } else {
                    Session::set('feedback_negative', 'Der var et problem med deres køb, du vil blive kontaktet af support snarest mulig.', true);
                    return false;
                }
            } else {
                Session::set('feedback_negative', 'Ugyldig token.');
                return false;
            }
        }
        return false;
    }

    /**
     * Removes the cart from Session.
     * @return mixed
     */
    public function clearCart()
    {
        Session::remove('user', 'cart');
    }

    /**
     * If any, returns the whole cart.
     * @return mixed
     */
    public function items()
    {
       return Session::get('user')['cart'];
    }
}