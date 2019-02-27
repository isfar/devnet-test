<?php

namespace Mo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class MoController extends AbstractActionController
{
    protected $_amqpConnection;

    public function indexAction() {
        // TODO: Sanitize the Input Data
        $data = $this->getRequest()->getQuery();
        $connection = $this->getAmqpConnection();
        $channel = $connection->channel();

        $channel->queue_declare('mo_save', false, false, false, false);
        
        // TODO: get it through dependency injection
        $message = new \PhpAmqpLib\Message\AMQPMessage(json_encode($data));

        $channel->basic_publish($message, '', 'mo_save');

        $channel->close();
        $connection->close();

        return new JsonModel([
            'success' => true,
            'payload' => [
                'message' => 'Your request will be processed shortly',
            ]
        ]);
    }

    public function setAmqpConnection(\PhpAmqpLib\Connection\AbstractConnection $conn) {
        $this->_amqpConnection = $conn;
        return $this;
    }

    public function getAmqpConnection() {
        return $this->_amqpConnection;
    }
}
