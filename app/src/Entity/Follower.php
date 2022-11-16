<?php

namespace App\Entity;

use App\Repository\FollowerRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: FollowerRepository::class), Table(name: 'followers')]
class Follower
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $id;
    #[Column(name: 'left_friend_id', type: 'integer', nullable: false)]
    protected int $leftFriendId;
    #[Column(name: 'right_friend_id',type: 'integer', nullable: false)]
    protected int $rightFriendId;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'left_friend_id', referencedColumnName: 'id')]
    private User|null $leftUser = null;
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'right_friend_id', referencedColumnName: 'id')]
    private User|null $rightUser = null;

    public function __construct(
        int $leftFriendId,
        int $rightFriendId
        )
    {
        $this->leftFriendId   = $leftFriendId;
        $this->rightFriendId  = $rightFriendId;
    }

    /**
     * @return int
     */
    public function getLeftFriendId(): int
    {
        return $this->leftFriendId;
    }

    /**
     * @param int $leftFriendId
     */
    public function setLeftFriendId(int $leftFriendId): void
    {
        $this->leftFriendId = $leftFriendId;
    }

    /**
     * @return int
     */
    public function getRightFriendId(): int
    {
        return $this->rightFriendId;
    }

    /**
     * @param int $rightFriendId
     */
    public function setRightFriendId(int $rightFriendId): void
    {
        $this->rightFriendId = $rightFriendId;
    }

    public function toArray(): array
    {
        return [
            'left' => $this->getLeftFriendId(),
            'right' => $this->getRightFriendId()
        ];
    }
}