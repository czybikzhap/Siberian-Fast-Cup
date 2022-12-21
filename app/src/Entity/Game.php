<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: GameRepository::class), Table(name: 'games')]
class Game
{
    #[Id, Column(name: 'game_id', type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $gameId;
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User $userId;
    #[Column(type: 'string', nullable: false)]
    protected string $white;
    #[Column(type: 'string', unique: true, nullable: false)]
    protected string $black;
    #[Column(type: 'string', nullable: false)]
    protected string $winner;
    #[Column(type: 'integer', unique: true, nullable: false)]
    protected int $whiteElo;
    #[Column(type: 'integer', nullable: false)]
    protected int $blackElo;
    #[Column(type: 'string', unique: true, nullable: false)]
    protected string $speed;
    #[Column(type: 'string', unique: true, nullable: false)]
    protected string $moves;

    public function __construct(
        User   $userId,
        string $white,
        string $black,
        string $winner,
        int    $whiteElo,
        int    $blackElo,
        string $speed,
        string $moves)
    {
        $this->userId  = $userId;
        $this->white    = $white;
        $this->black    = $black;
        $this->winner   = $winner;
        $this->whiteElo = $whiteElo;
        $this->blackElo = $blackElo;
        $this->speed    = $speed;
        $this->moves    = $moves;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @return string
     */
    public function getWhite(): string
    {
        return $this->white;
    }

    /**
     * @param string $white
     */
    public function setWhite(string $white): void
    {
        $this->white = $white;
    }

    /**
     * @return string
     */
    public function getBlack(): string
    {
        return $this->black;
    }

    /**
     * @param string $black
     */
    public function setBlack(string $black): void
    {
        $this->black = $black;
    }

    /**
     * @return string
     */
    public function getWinner(): string
    {
        return $this->winner;
    }

    /**
     * @param string $winner
     */
    public function setWinner(string $winner): void
    {
        $this->winner = $winner;
    }

    /**
     * @return string
     */
    public function getWhiteElo(): int
    {
        return $this->whiteElo;
    }

    /**
     * @param int $whiteElo
     */
    public function setWhiteElo(int $whiteElo): void
    {
        $this->whiteElo = $whiteElo;
    }

    /**
     * @return int
     */
    public function getBlackElo(): int
    {
        return $this->blackElo;
    }

    /**
     * @param int $blackElo
     */
    public function setBlackElo(int $blackElo): void
    {
        $this->blackElo = $blackElo;
    }

    /**
     * @return string
     */
    public function getSpeed(): string
    {
        return $this->speed;
    }

    /**
     * @param string $speed
     */
    public function setSpeed(string $speed): void
    {
        $this->speed = $speed;
    }

    /**
     * @return string
     */
    public function getMoves(): string
    {
        return $this->moves;
    }

    /**
     * @param string $moves
     */
    public function setMoves(string $moves): void
    {
        $this->moves = $moves;
    }

    /**
     * @return User
     */
    public function getUserId(): User
    {
        return $this->userId;
    }

    /**
     * @param User $userId
     */
    public function setUserId(User $userId): void
    {
        $this->userId = $userId;
    }
}