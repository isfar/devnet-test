<?php

namespace Mo;

use Zend\Router\Http\Literal;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Tools\Setup;
use Interop\Container\ContainerInterface;

return [
    'doctrine' => [
        'dirs' => [
            __DIR__ . "src/Model/Entity",
        ],
    ],
    'rabbitmq' => [
        'hostname' => 'localhost',
        'port' => 5672,
        'username' => 'guest',
        'password' => 'guest',
    ],
    'router' => [
        'routes' => [
            'mo' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/mo',
                    'defaults' => [
                        'controller' => Controller\MoController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
                'mo-consumer' => [
                    'type' => 'simple',
                    'options' => [
                        'route' => 'mo-consumer',
                        'defaults' => [
                            'controller' => Controller\MoConsumerController::class,
                            'action' => 'index',
                        ],
                    ],
                ]
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            \Mo\Controller\MoController::class => \Mo\Controller\Factory\MoControllerFactory::class,
            \Mo\Controller\MoConsumerController::class => \Mo\Controller\Factory\MoConsumerControllerFactory::class,
        ]
    ],
    'service_manager' => [
        'factories' => [
            \Doctrine\ORM\EntityManager::class => function (ContainerInterface $sm, $requestedName, $options = null) {
                $connConfig = $sm->get('Config')['db'];
                $dirs = $sm->get('Config')['doctrine']['dirs'];
                $config = Setup::createAnnotationMetadataConfiguration(
                    $dirs,
                    true
                );

                return \Doctrine\ORM\EntityManager::create($connConfig, $config);
            },
            \PhpAmqpLib\Connection\AMQPStreamConnection::class => function (ContainerInterface $sm, $requestedName, $options = null) {
                $config = $sm->get('Config')['rabbitmq'];
                $connection = new \PhpAmqpLib\Connection\AMQPConnection(
                    $config['hostname'], $config['port'], $config['username'], $config['password']
                );
                return $connection;
            }
        ],
    ],
    'db' => [
        'driver' => 'pdo_mysql',
        'hostname' => 'localhost',
        'user' => 'test',
        'password' => '123456',
        'dbname' => 'testing',
    ],
];
