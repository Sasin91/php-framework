<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 20-05-15
 * Time: 12:49
 */

namespace System\Interfaces;


interface Messages {

    /**
     * Parse the message
     * @param string $method
     * @param $message
     */
    public function parseMessage($receiver, $sender, $message);

    /**
     * Handle that message.
     */
    public function handleMessage($type, $receiver, $sender, $message);
}