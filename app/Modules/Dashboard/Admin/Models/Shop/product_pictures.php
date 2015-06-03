<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 01-06-15
 * Time: 23:19
 */

namespace Modules\Dashboard\Admin\Models\Shop;


class product_pictures extends Products {

    protected $belongsTo = 'Modules\Dashboard\Admin\Models\Shop\Products';

    protected $table = 'shop_product_pictures';

}