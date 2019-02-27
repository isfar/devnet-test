<?php

namespace Mo\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class MoConsumerController extends AbstractActionController
{
    protected $_entityManager;
    protected $_amqpConnection;

    public function indexAction()
    {
        $connection = $this->getAmqpConnection();
        $channel = $connection->channel();

        $channel->queue_declare('mo_save', false, false, false, false);

        $channel->basic_consume('mo_save', '', false, true, false, false, [$this, 'save']);

        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }

    public function save($message) {
        $params= json_decode($message->body);

        $mo = new \Mo\Model\Entity\Mo();

        $mo->setMsisdn($params->msisdn)
            ->setAuthToken($this->getAuthToken($message->body))
            ->setOperatorId($params->operatorid)
            ->setShortcodeId($params->shortcodeid)
            ->setText($params->text)
            ->setCreatedAt(new \DateTime('now'));

        $this->getEntityManager()->persist($mo);
        $this->getEntityManager()->flush();

        echo json_encode([
            'success' => true,
            'payload' => [
                'id' => $mo->getId(),
                'token' => $mo->getAuthToken(),
            ],
        ]) . PHP_EOL;
    }

	protected function getAuthToken($args) {
        return `./bin/registermo $args`; 
	}

    public function setEntityManager(\Doctrine\ORM\EntityManagerInterface $entityManager) {
        $this->_entityManager = $entityManager;
        return $this;
    }

    public function getEntityManager(): \Doctrine\ORM\EntityManagerInterface {
        return $this->_entityManager;
    }
    
    public function setAmqpConnection(\PhpAmqpLib\Connection\AbstractConnection $conn) {
        $this->_amqpConnection = $conn;
        return $this;
    }

    public function getAmqpConnection(): \PhpAmqpLib\Connection\AbstractConnection {
        return $this->_amqpConnection;
    }
}
