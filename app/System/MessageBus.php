<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 20-05-15
 * Time: 10:30
 */

namespace System;


class MessageBus {

    /**
     * Inter-app messages
     * @var array
     */
    public $messages = array();

    /**
     * Inter-app message responses
     * @var array
     */
    public $responses = array();

    /**
     * Instance of Kernel
     * @var
     */
    protected $dispatch;

    /**
     * Type of delivery, response or message.
     * @var string
     */
    private $deliveryType = '';

    private $receiver;

    private $sender;

    /**
     * @param Kernel $dispatch
     */
    public function __construct(Dispatch $dispatch)
    {
        $this->dispatch = $dispatch;
    }

    /**
     * Send a new message
     * @param $sender
     * @param $receiver
     * @param $message
     * @return $this
     */
    public function send($sender, $receiver, $message)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->deliveryType = 'message';
        $this->messages[$receiver] = array($sender => $message);
        return $this;
    }

    /**
     * Respond to a message
     * @param $receiver
     * @param $message
     * @return $this
     */
    public function respond($sender, $receiver, $message)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->deliveryType = 'response';
        $this->responses[$receiver] = $message;
        return $this;
    }

    /**
     * Return all undelivered messages
     * @return mixed
     */
    public function available()
    {
        return $this->messages;
    }

    /**
     * Deliver the messages for receiver
     * @param string $receiver
     */
    public function deliver($method = '')
    {
            foreach ($this->messages[$this->receiver] as $message) {
                $this->dispatch->fire($this->receiver, $this->sender, $message, $method);
            }
            /**
             * After the messages have been delivered, empty (or truncate, if you prefer that term) the "postbox"
             */
            $this->messages[$this->receiver] = array();
    }
}