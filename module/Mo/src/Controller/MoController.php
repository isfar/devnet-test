<?php

namespace Mo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class MoController extends AbstractActionController
{
    protected $_amqpConnection;
    protected $_entityManager;
    protected $_queue;

    public function indexAction() {
        // TODO: Sanitize the Input Data
        $data = $this->getRequest()->getQuery();

        $queue = $this->getQueue();
        $queue->init();
        $queue->send(json_encode($data));
        $queue->close();

        return new JsonModel([
            'success' => true,
            'payload' => [
                'message' => 'Your request will be processed shortly',
            ]
        ]);
    }

    public function statsAction() {
        // TODO: Move these codes to a Stats class
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(mo.id)')
            ->from(\Mo\Model\Entity\Mo::class, 'mo')
            ->where('mo.createdAt > :datetime')
            ->setParameter('datetime', new \DateTime('15 minutes ago'));

        $count = $qb->getQuery()->getSingleScalarResult();

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('MIN(mo.createdAt) AS min, MAX(mo.createdAt) AS max')
            ->from(\Mo\Model\Entity\Mo::class, 'mo')
            ->orderBy('mo.createdAt', 'DESC')
            ->setMaxResults(10000);
        
        $result = $qb->getQuery()->getSingleResult();

        return new JsonModel([
            'success' => true,
            'payload' => [
                'last_15_mins_mo_count' => $count,
                'time_span_last_10k' => $result,
            ],
        ]);
    }

    public function getUnprocessedCountAction() {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(mo.id)')
            ->from(\Mo\Model\Entity\Mo::class, 'mo')
            ->where('mo.isProcessed = :processed')
            ->setParameter('processed', 0);

        $unprocessedCount = $qb->getQuery()->getSingleScalarResult();
        echo "Total unprocessed MO's: $unprocessedCount." . PHP_EOL;
    }

    public function removeUnprocessedAction() {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->delete()
            ->from(\Mo\Model\Entity\Mo::class, 'mo')
            ->where('mo.isProcessed = :processed')
            ->setParameter('processed', 0);

        $qb->getQuery()->execute();

        echo "All unprocessed MO's are now deleted." . PHP_EOL;
    }

    public function setQueue(\Mo\Queue\QueueInterface $queue) {
        $this->_queue = $queue;
        return $this;
    }

    public function getQueue(): \Mo\Queue\QueueInterface {
        return $this->_queue;
    }

    public function setEntityManager(\Doctrine\ORM\EntityManagerInterface $entityManager) {
        $this->_entityManager = $entityManager;
        return $this;
    }

    public function getEntityManager(): \Doctrine\ORM\EntityManagerInterface {
        return $this->_entityManager;
    }
}
