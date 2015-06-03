<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 21-05-15
 * Time: 21:35
 */

namespace System\Input;

/**
 * Class InputHandler
 * @package System\Input
 */
class InputHandler {

    /**
     * Instance of Input subclass
     * @var Post
     */
    public $input;

    /**
     * @param $method
     * @param $inputArguments
     */
    public function __construct($method, $inputArguments)
    {
        switch($method)
        {
            case('GET'):
                $this->input = new Get($inputArguments);
            break;

            case('POST'):
                $this->input = new Post($inputArguments);
            break;
        }

        return $this->input;
    }

}