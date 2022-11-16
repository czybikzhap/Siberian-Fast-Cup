<?php
namespace App\Repository;

use App\Entity\Follower;

class FollowerRepository extends \Doctrine\ORM\EntityRepository
{
    public function add(Follower $entity, bool $flush = false): void
    {
        #persist сохранение в памяти, flush сохранение в бд
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByFollower(int $idLeft, int $idRight): ?Follower
    {
        return $this->findOneBy(['left_friend_id' => $idLeft, 'right_friend_id' => $idRight]);
    }

    public function delete(Follower $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}