<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 25-05-2015
 * Time: 03:11
 */

namespace Modules\Dashboard\Admin;

use Modules\Dashboard\Admin\Models\Pages;
use Modules\Dashboard\Admin\Models\Shop;
use Modules\Dashboard\BaseDashboard;
use System\Exception\BadQueryException;
use System\Input\Input;

class Dashboard extends BaseDashboard {

    protected $shop_options = ['categories', 'products', 'services', 'pictures', 'orders'];

    public function pages(Input $request)
    {
        $this->handle($request->arguments, new Pages());
    }

    public function shop(Input $request)
    {
        $args =  $request->arguments;
        $model = new Shop(); // default table = products

        if(isset($args[1])) {
            if (in_array($args[1], $this->shop_options)) {
                if(!isset($args[2]))
                {
                    $method = 'get'.ucfirst($args[1]);
                    $this->view->render('Dashboard/Admin/'.$args[1], array(
                        'title' => 'Shop Admin | '.$args[1],
                        'data' => $model->$method()
                    ));
                } else {
                    if ($args[1] == 'pictures') {
                        $args[1] = 'product_pictures';
                    }
                    if ($args[1] == 'orders') {
                        $args[1] = 'purchased';
                    }
                    $model->setTable('shop_' . $args[1]);
                    $this->handle($args, $model);
                }
            } else {
                throw new BadQueryException($args[1].' was not found in shop_options[] @'.__CLASS__);
            }
        }else {
            $this->view->render('Dashboard/Admin/shop', array(
                'title' => 'Shop Administration',
                'orders' => $model->getOrders()
                )
            );
        }

    }

    protected function handle(array $args, $model)
    {
        if(isset($args[1]))
            if(isset($args['post']))
            {
                if(array_keys($args['post'])[0] == 'undefined')
                {
                    $args['post'] = $args[0];
                    unset($args[0]);
                }

                $model->handlePost(array_pop($args), $args['post']);

            }
            else
            {
                print_r(json_encode($model->handleGet($args)));

            }
    }
}