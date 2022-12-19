<?php

namespace App\Entity;
use App\Repository\MessageRepository;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: MessageRepository::class), Table(name: 'messages')]
class MessageHistory
{
    #[Id, Column(name: 'messages_id', type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $messagesId;
    #[Column(name: 'messages_text', type: 'string')]
    protected string $messagesText;
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'sender_user_id', referencedColumnName: 'id')]
    private User $senderUserId;
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'receiver_user_id', referencedColumnName: 'id')]
    private User $receiverUserId;
    #[Column(type: 'string', nullable: false)]
    protected string $status;
    #[Column(name: 'date_time', type: 'datetime', nullable: false)]
    protected DateTime $dateTime;

    public function __construct(
        string   $messagesText,
        User     $senderUserId,
        User     $receiverUserId,
        string   $status,
        DateTime $dateTime
    )
    {
        $this->messagesText     = $messagesText;
        $this->senderUserId     = $senderUserId;
        $this->receiverUserId   = $receiverUserId;
        $this->status           = $status;
        $this->dateTime         = $dateTime;
    }

    /**
     * @return string
     */
    public function getMessagesText(): string
    {
        return $this->messagesText;
    }

    /**
     * @param string $messagesText
     */
    public function setMessagesText(string $messagesText): void
    {
        $this->messagesText = $messagesText;
    }

    /**
     * @return User
     */
    public function getSenderUserId(): User
    {
        return $this->senderUserId;
    }

    /**
     * @param User $senderUserId
     */
    public function setSenderUserId(User $senderUserId): void
    {
        $this->senderUserId = $senderUserId;
    }

    /**
     * @return User
     */
    public function getReceiverUserId(): User
    {
        return $this->receiverUserId;
    }

    /**
     * @param User $receiverUserId
     */
    public function setReceiverUserId(User $receiverUserId): void
    {
        $this->receiverUserId = $receiverUserId;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return DateTime
     */
    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    /**
     * @param DateTime $dateTime
     */
    public function setDateTime(DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }
}