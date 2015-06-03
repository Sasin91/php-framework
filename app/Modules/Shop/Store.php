<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 21-04-15
 * Time: 17:23
 */

namespace Modules\Shop;

use System\Exception\CartException;

/**
 * Class Store
 * @package Core\Models
 */
class Store implements \Countable, \Iterator
{
    public $model;

    protected $items = array();

    protected $categories = array();

    protected $services = array();

    protected $ids = array(); // for quick access

    protected $position = 0; // for Iterator

    /**
     * @param array $fillable
     * @throws \System\Exception\DatabaseException
     */
    public function __construct(array $fillable = array())
    {
        $this->model = new StoreModel($fillable);
        $this->populate();
    }

    /**
     * Populate our array of items with a new class of a given item.
     */
    public function populate()
    {
        $items = $this->model->query("SELECT * FROM shop_products
          INNER JOIN shop_categories ON  shop_products.fk_categories_id = shop_categories.category_id
          LEFT JOIN shop_product_pictures ON shop_products.id = shop_product_pictures.fk_product_id
          LEFT JOIN shop_services ON shop_products.id = shop_services.fk_product_id");
        array_walk_recursive($items, function ($item, $key) {
            $pictures = array(
                $item->picture_id => array(
                    'path' => $item->path,
                    'placement' => $item->placement
                ));
            $this->newCategory($item->category, $item->category_description, $item->category_link, $item->category_pic);
            $this->newService($item->service, $item->service_price, $item->service_description);
            $this->newItem($item->category, $item->id, $item->label, $item->qty, $item->price, $pictures);
        });
    }

    /**
     * return what we have in store.
     * @return array
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * return which categories we offer.
     * @return array
     */
    public function services()
    {
        return $this->services;
    }

    /**
     * Return categories
     * @return array
     */
    public function categories()
    {
        return $this->categories;
    }

    public function allCategories()
    {
        return $this->model->select('*', 'shop_categories')->execute();
    }

    /**
     * @param $item
     * @return mixed
     */
    public function getItem($item)
    {
        return $this->items[$item];
    }

    /**
     * Create a new Service
     * @param $service
     * @param $price
     * @param $description
     */
    public function newService($service, $price, $description)
    {
        return $this->services[$service] = new Service($service, $price, $description);
    }

    /**
     * Create a new Category.
     * @param $category
     * @param $description
     * @param $picture
     * @return Category
     */
    public function newCategory($category, $description, $link, $picture)
    {
        return $this->categories[$category] = new Category($category, $description, $link, $picture);
    }

    /**
     * Create a new Item
     * @param $category
     * @param $id
     * @param $label
     * @param $qty
     * @param $price
     * @param array $pictures
     * @return Item
     */
    public function newItem($category, $id, $label, $qty, $price, array $pictures = array())
    {
        $this->ids[] = $id;
        return $this->items[$label] = new Item($category, $id, $label, $qty, $price, $pictures);
    }

    public function addItem(Item $item)
    {

        // Need the item id:
        $id = $item->getId();

        // Throw an exception if there's no id:
        if (!$id) throw new CartException('The cart requires items with unique ID values.');

        // Add or update:
        if (isset($this->items[$id])) {
            $this->updateItem($item, $this->items[$item]['qty'] + 1);
        } else {
            $this->items[$id] = array('item' => $item, 'qty' => 1);
            $this->ids[] = $id; // Store the id, too!
        }

    }

    public function updateItem(Item $item, $qty)
    {

        // Need the unique item id:
        $id = $item->getId();

        // Delete or update accordingly:
        if ($qty === 0) {
            $this->deleteItem($item);
        } elseif (($qty > 0) && ($qty != $this->items[$id]['qty'])) {
            $this->items[$id]['qty'] = $qty;
        }

    }


    /**
     * Subtract from an item.
     * @param $element
     */
    private $call;
    private $element;

    public function subtract($element)
    {
        $this->element = $element;
        $this->call = __FUNCTION__;
        return $this;
    }

    /**
     * What to act upon.
     * @param array $items
     */
    public function from(array $items = array())
    {
        foreach ($items as $item) {
            $this->action($this->call, $item, $this->element, $item->qty);
        }
    }

    /**
     * The actual action.
     * @param $action
     * @param $item
     * @param $element
     * @param $num
     */
    private function action($action, $item, $element, $num)
    {
        if ($action == 'subtract') {
            $this->items[$item->id][$element] = $num;
        }
    }

    /**
     * Delete item
     * @param Item $item
     */
    public function deleteItem(Item $item)
    {
        // Need the unique item id:
        $id = $item->getId();

        // Remove it:
        if (isset($this->items[$id])) {
            unset($this->items[$id]);

            // Remove the stored id, too:
            $index = array_search($id, $this->ids);
            unset($this->ids[$index]);

            // Recreate that array to prevent holes:
            $this->ids = array_values($this->ids);
        }
    }

    /**
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    // Required by Iterator; returns the current value:
    public function current()
    {

        // Get the index for the current position:
        $index = $this->ids[$this->position];

        // Return the item:
        return $this->items[$index];

    } // End of current() method.

    // Required by Iterator; returns the current key:
    public function key()
    {
        return $this->position;
    }

    // Required by Iterator; increments the position:
    public function next()
    {
        $this->position++;
    }

    // Required by Iterator; returns the position to the first spot:
    public function rewind()
    {
        $this->position = 0;
    }

    // Required by Iterator; returns a Boolean indicating if a value is indexed at this position:
    public function valid()
    {
        return (isset($this->ids[$this->position]));
    }
}