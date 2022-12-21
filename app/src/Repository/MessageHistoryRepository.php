<?php

namespace App\Repository;

use App\Entity\MessageHistory;
use Doctrine\ORM\EntityRepository;

class MessageHistoryRepository extends EntityRepository
{
    public function add(MessageHistory $entity, bool $flush = false): void
    {
        #persist сохранение в памяти, flush сохранение в бд
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}