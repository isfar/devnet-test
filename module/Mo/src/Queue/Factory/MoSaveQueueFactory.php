<?php

namespace Mo\Queue\Factory;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;


class MoSaveQueueFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $sm, $requestedName, $options = null)
    {
        $moSaveQueue = new \Mo\Queue\MoSaveQueue();
        $moSaveQueue->setAmqpConnection($sm->get(\PhpAmqpLib\Connection\AMQPStreamConnection::class));
        return $moSaveQueue;
    }
}
