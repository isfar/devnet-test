<?php

namespace Mo\Queue;

class MoSaveQueue implements QueueInterface
{
    const QUEUE_NAME = 'mo_save';

    protected $_amqpConnection;
    protected $_channel;
    
    public function init() {
        $this->_channel = $this->getAmqpConnection()->channel();
        $this->_channel->queue_declare(self::QUEUE_NAME, false, false, false, false);
    }

    public function close() {
        if (!$this->getChannel())
            $this->getChannel()->close();
        if (!$this->getAmqpConnection()) 
            $this->getAmqpConnection()->close();
    }

    public function send(string $message) {
        $this->getChannel()->basic_publish(new \PhpAmqpLib\Message\AMQPMessage($message), '', self::QUEUE_NAME);
    }

    public function consume(callable $callback) {
        $this->getChannel()->basic_consume(self::QUEUE_NAME, '', false, true, false, false, $callback);
    }

    public function wait() {
        while(count($this->getChannel()->callbacks)) {
            $this->getChannel()->wait();
        }
    }

    public function setChannel(\PhpAmqpLib\Channel\AbstractChannel $channel) {
        $this->_channel = $channel;
        return $this;
    }

    public function getChannel(): \PhpAmqpLib\Channel\AbstractChannel {
        return $this->_channel;
    }
    
    public function setAmqpConnection(\PhpAmqpLib\Connection\AbstractConnection $conn) {
        $this->_amqpConnection = $conn;
        return $this;
    }

    public function getAmqpConnection(): \PhpAmqpLib\Connection\AbstractConnection {
        return $this->_amqpConnection;
    }
}
