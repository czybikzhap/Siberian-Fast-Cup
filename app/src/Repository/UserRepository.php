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

    public function findOneByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function findOneByToken(array $token): ?User
    {
        return $this->findOneBy(['token' => $token[0]]);
    }

    public function delete(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
