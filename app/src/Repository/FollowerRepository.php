<?php
namespace App\Repository;

use App\Entity\Subscrib;

class FollowerRepository extends \Doctrine\ORM\EntityRepository
{
    public function add(Subscrib $entity, bool $flush = false): void
    {
        #persist сохранение в памяти, flush сохранение в бд
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByFollower(int $idLeft, int $idRight): ?Subscrib
    {
        return $this->findOneBy(['left_friend_id' => $idLeft, 'right_friend_id' => $idRight]);
    }

    public function delete(Subscrib $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}