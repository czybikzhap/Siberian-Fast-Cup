<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\ORM\EntityRepository;

class MessageRepository extends EntityRepository
{
    public function add(Message $entity, bool $flush = false): void
    {
        #persist сохранение в памяти, flush сохранение в бд
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function delete(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}