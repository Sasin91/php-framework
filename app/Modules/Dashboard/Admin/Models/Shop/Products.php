<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 01-06-15
 * Time: 23:18
 */

namespace Modules\Dashboard\Admin\Models\Shop;


use Modules\Dashboard\Admin\Models\ChainMethodShop;

class Products extends ChainMethodShop {

    protected $table = 'shop_products';

    public function getPictures($product_id)
    {
        return $this->hasOne('Modules\Dashboard\Admin\Models\Shop\product_pictures')->find($product_id, 'fk_product_pictures');
    }

}