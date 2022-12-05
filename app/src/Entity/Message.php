<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use DateTime;

#[Entity(repositoryClass: MessageRepository::class), Table(name: 'messages')]
class Message{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $messages_id;
    #[Column(type: 'string')]
    protected string $messages_text;
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'sender_user_id', referencedColumnName: 'id')]
    private User $sender_user_id;
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'sender_user_id', referencedColumnName: 'id')]
    private User $receiver_user_id;
    #[Column(type: 'string', nullable: false)]
    protected string $status;
    #[Column(type: 'DATE', nullable: false)]
    protected DateTime $data;

    public function __construct(
        string $messages_text,
        User $sender_user_id,
        User $receiver_user_id,
        string $status,
        DateTime $data
        )
    {
        $this->messages_text    = $messages_text;
        $this->sender_user_id   = $sender_user_id;
        $this->receiver_user_id = $receiver_user_id;
        $this->status           = $status;
        $this->data             = $data;
    }

    /**
     * @return int
     */
    public function getMessagesId(): int
    {
        return $this->messages_id;
    }

    /**
     * @return string
     */
    public function getMessagesText(): string
    {
        return $this->messages_text;
    }

    /**
     * @param string $messages_text
     */
    public function setMessagesText(string $messages_text): void
    {
        $this->messages_text = $messages_text;
    }

    /**
     * @return User
     */
    public function getSenderUserId(): User
    {
        return $this->sender_user_id;
    }

    /**
     * @param User $sender_user_id
     */
    public function setSenderUserId(User $sender_user_id): void
    {
        $this->sender_user_id = $sender_user_id;
    }

    /**
     * @return User
     */
    public function getReceiverUserId(): User
    {
        return $this->receiver_user_id;
    }

    /**
     * @param User $receiver_user_id
     */
    public function setReceiverUserId(User $receiver_user_id): void
    {
        $this->receiver_user_id = $receiver_user_id;
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
    public function getData(): DateTime
    {
        return $this->data;
    }

    /**
     * @param DateTime $data
     */
    public function setData(DateTime $data): void
    {
        $this->data = $data;
    }
}