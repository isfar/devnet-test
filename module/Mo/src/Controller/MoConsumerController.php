<?php

namespace Mo\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class MoConsumerController extends AbstractActionController
{
    protected $_entityManager;
    protected $_amqpConnection;
    protected $_queue;

    public function indexAction()
    {
        $queue = $this->getQueue();
        $queue->init();
        $queue->consume([$this, 'save']);
        $queue->wait();
    }

    public function save($message) {
        $params = json_decode($message->body);

        $mo = new \Mo\Model\Entity\Mo();

        $mo->setMsisdn($params->msisdn)
            ->setAuthToken($this->getAuthToken($message->body))
            ->setOperatorId($params->operatorid)
            ->setShortcodeId($params->shortcodeid)
            ->setText($params->text)
            ->setCreatedAt(new \DateTime('now'))
            ->setProcessed(true);

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

    public function setQueue(\Mo\Queue\QueueInterface $queue) {
        $this->_queue = $queue;
        return $this;
    }

    public function getQueue(): \Mo\Queue\QueueInterface {
        return $this->_queue;
    }
}
