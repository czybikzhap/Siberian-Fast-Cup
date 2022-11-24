<?php

namespace App\Entity;

use App\Repository\SubscribRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: SubscribRepository::class), Table(name: 'subscrib')]
class Subscrib
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $id;
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'left_friend_id', referencedColumnName: 'id')]
    private User $leftUser;
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'right_friend_id', referencedColumnName: 'id')]
    private User $rightUser;

    public function __construct(
        User $leftUser,
        User $rightUser
        )
    {
        $this->leftUser   = $leftUser;
        $this->rightUser  = $rightUser;
    }

    /**
     * @return User
     */
    public function getLeftUser(): User
    {
        return $this->leftUser;
    }

    /**
     * @param User $leftUser
     */
    public function setLeftUser(User $leftUser): void
    {
        $this->leftUser = $leftUser;
    }

    /**
     * @return User
     */
    public function getRightUser(): User
    {
        return $this->rightUser;
    }

    /**
     * @param User $rightUser
     */
    public function setRightUser(User $rightUser): void
    {
        $this->rightUser = $rightUser;
    }
}