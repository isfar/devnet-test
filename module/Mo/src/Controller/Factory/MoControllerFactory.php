<?php

namespace Mo\Controller\Factory;

use Mo\Controller\MoController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;


class MoControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $sm, $requestedName, $options = null)
    {
        $moController = new MoController();
        $moController->setEntityManager($sm->get(\Doctrine\ORM\EntityManager::class));
        $moController->setQueue($sm->get(\Mo\Queue\MoSaveQueue::class));
        return $moController;
    }
}
