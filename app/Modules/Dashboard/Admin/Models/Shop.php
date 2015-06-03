<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 26-05-15
 * Time: 10:35
 */

namespace Modules\Dashboard\Admin\Models;


use Modules\Dashboard\Admin\Models\Shop\Categories;
use Modules\Dashboard\Admin\Models\Shop\Orders;
use Modules\Dashboard\Admin\Models\Shop\Products;
use Modules\Dashboard\BaseModel;

class Shop extends BaseModel {

    protected $table = 'shop_products';

    public function __construct()
    {
        parent::__construct(
            array(
                'id',
                'label',                #//
                'short_description',    //
                'description',          //  Products
                'qty',                  //
                'price',                //
                'fk_categories_id',    #//

                'path',                 #//
                'picture_id',           //  Pictures
                'placement',           #//

                'fk_product_id',        #//
                'service_price',         // Services
                'service_desciption',   #//

                'category',               #//
                'category_description',   // Categories
                'category_pic',          //
                'category_link',       #//
            )
        );
    }

    public function getProducts()
    {
        return Products::get()->finish();
    }

    public function getCategories()
    {
        return Categories::get()->finish();
    }

    public function getOrders()
    {
        return Orders::get()->finish();
    }

    public function getOrder($user_id)
    {
        return Orders::get()->withWhereClause()->column('user_id')->equals()->value($user_id)->finish();
    }
}