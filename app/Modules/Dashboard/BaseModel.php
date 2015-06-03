<?php
/**
 * Created by PhpStorm.
 * User: Jonas
 * Date: 25-05-2015
 * Time: 19:59
 */

namespace Modules\Dashboard;


use System\Authentication\Session;
use System\Exception\BadMethodCallException;
use System\Exception\BadQueryException;
use System\MVC\Model;
use Toolbox\ArrayTools;

class BaseModel extends Model {

    protected $belongsTo = 'Modules\Dashboard\BaseDashboard';

    /**
     * Array of pages and their respective column upon which can be requested to
     * @var array
     */
    protected $getCandidates = array(
        'pages' => 'slug',
        'gallery' => 'id',
        'images' => array('id', 'description'),
        'teams' => array('id', 'status', 'description', 'age_group'),
        'shop_categories' => array('category_id', 'category'),
        'shop_products' => 'label',
        'shop_product_pictures' => array('path'),
        'members' => 'uid',
        'shop_purchased' => array('user_id', 'username', 'recipient', 'item', 'delivered')

    );

    /**
     * Array containing requests already made on API, intended to save database queries.
     * @var array
     */
    public $storedApiRequests = array();

    public function __construct(array $permitted)
    {
        parent::__construct($permitted);
    }

    /**
     * @param $method
     * @param array $requests
     * @throws BadMethodCallException
     * @throws BadQueryException
     */
    public function handlePost($method, array $requests)
    {
        if($method == 'create')
        {
            $method = 'insert';
        }

        if($method == 'modify') // special method that will handle both insert, delete, update at once.
        {
            return $this->modify($requests, $this->getCandidates[$this->table]);
        }

        if(method_exists($this, $method)) {

            if($method != 'insert')
            {
                if(isset($requests['where']))
                {
                    $query = explode(', ', $requests['where']);
                    $column = $query[0];
                    $operand = '=';
                    $value = $query[1];

                    unset($requests['where']);

                } else {

                    foreach ($requests as $k => $v) {
                        $candidates = $this->getCandidates[$this->table];
                        if(is_array($candidates))
                        {
                            if(in_array($k, $candidates )) {
                                $column = $k;
                                $operand = '=';
                                $value = $v;
                            }

                        } else {

                        if($k == $candidates)
                            {
                                $column = $k;
                                $operand = '=';
                                $value = $v;
                            }

                        }

                    }

                }
                $this->$method(chop(FormatQuery::$method($requests), ', '), $this->table)->where($column, $operand, $value)->execute();
                Session::set('feedback_positive', $method.'d your request successfully');
                return \app::redirect('dashboard');
            } else {

                $data =  FormatQuery::$method($requests);
                $columns = $data['columns'];
                $values = $data['values'];

                $this->$method($columns, $values, $this->table)->execute();

            }

        } else {

            throw new BadMethodCallException(' '.$method . '() in ' . __CLASS__.' ');
        }
    }

    /**
     * @param array $requests
     * @return array|mixed
     */
    public function handleGet(array $requests)
    {
        array_shift($requests); // exclude first key { because it is the foregoing method }

        if($requests[1] == 'describe')
        {
            return $this->describe($this->table);
        }

        foreach ($requests as $key => $request) {

            if(in_array($request, array_keys($this->storedApiRequests)))
            {
                return $this->storedApiRequests[$request];
            }

            if(is_numeric($key))
            {
                if(in_array($this->table, array_keys($this->getCandidates)))
                {
                    $candidates = $this->getCandidates[$this->table];
                    if(is_array($candidates))
                    {
                        foreach ($candidates as $candidate) {
                            $this->storedApiRequests[$request] = $this->find($request, $candidate, $this->table);
                        }

                    }
                    else // $candidates is NOT array
                    {
                        $this->storedApiRequests[$request] = $this->find($request, $candidates, $this->table);
                    }
                }
            }
            else // $key is NOT numeric
            {
                $this->storedApiRequests[$request] = $this->find($request, $key, $this->table);
            }
            $matches = !empty($this->storedApiRequests[$request]) ? $this->storedApiRequests[$request] : false;
        }
        return $matches;
    }

    protected function modify($requests, $candidates)
    {
        $isMulti = ArrayTools::isMultidimensional($requests);
        foreach ($requests as $key => $value) {
            if(in_array($key, (array)$candidates))
            {
                $attempt = $this->find($value, $key, $this->table);
                if(!empty($attempt)) {
                    if ($isMulti) {
                        for ($i = 0; $i < count($requests, COUNT_RECURSIVE); $i++) {
                            $this->update($requests[$i], $this->table)->where($key, '=', $value)->execute();
                        }
                    } else {
                        $this->update($requests, $this->table)->where($key, '=', $value)->execute();
                    }
                }
                elseif(empty($value))
                {

                  $this->delete($this->table)->where($key, '=', $value)->execute();

                } else {
                    if ($isMulti) {
                        for ($i = 0; $i < count($requests, COUNT_RECURSIVE); $i++) {
                            $this->insert(array_keys($requests[$i]), array_values($requests[$i]), $this->table);
                        }
                    } else {
                        $this->insert(array_keys($requests), array_values($requests), $this->table);
                    }
                }
            }
        }
        Session::set('feedback_positive', 'Change request successfully executed.');
        return \app::redirect('dashboard/shop');
    }
}