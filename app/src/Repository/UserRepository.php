<?php
namespace App\Repository;

use App\Entity\User;

class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function add(User $entity, bool $flush = false): void
    {
        #persist сохранение в памяти, flush сохранение в бд
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
