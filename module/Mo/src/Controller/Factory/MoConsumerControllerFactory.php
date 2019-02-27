<?php

namespace Mo\Controller\Factory;

use Mo\Controller\MoConsumerController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;


class MoConsumerControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $sm, $requestedName, $options = null)
    {
        $moConsumerController = new MoConsumerController();
        $moConsumerController->setEntityManager($sm->get(\Doctrine\ORM\EntityManager::class));
        $moConsumerController->setAmqpConnection($sm->get(\PhpAmqpLib\Connection\AMQPStreamConnection::class));
        return $moConsumerController;
    }
}
