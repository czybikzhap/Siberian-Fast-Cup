<?php
namespace App\Repository;

use App\Entity\Subscribe;
use Doctrine\ORM\EntityRepository;

class SubscribeRepository extends EntityRepository
{
    public function add(Subscribe $entity, bool $flush = false): void
    {
        #persist сохранение в памяти, flush сохранение в бд
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByFollower(int $idLeft, int $idRight): ?Subscribe
    {
        return $this->findOneBy(['left_friend_id' => $idLeft, 'right_friend_id' => $idRight]);
    }

    public function delete(Subscribe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}