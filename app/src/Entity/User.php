<?php
namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: UserRepository::class), Table(name: 'users')]
class User{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    protected int $id;
    #[Column(type: 'string', unique: true, nullable: false)]
    protected string $lastname;
    #[Column(type: 'string', unique: true, nullable: false)]
    protected string $firstname;
    #[Column(type: 'string', unique: true, nullable: false)]
    protected string $secondname;
    #[Column(type: 'string', unique: true, nullable: false)]
    protected string $email;
    #[Column(type: 'string', unique: true, nullable: false)]
    protected string $password;
    #[Column(type: 'string', unique: true, nullable: false)]
    protected string $phone;
    #[Column(type: 'integer', unique: true, nullable: false)]
    protected int $age;

    public function __construct(string $lastname, string $firstname,
                                string $secondname, string $email,
                                string $password, string $phone, int $age)
    {
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->secondname = $secondname;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->age = $age;
    }

    public function setLastName(string $lastname)
    {
        $this->lastname = $lastname;
    }

    public function setFirstName(string $firstname)
    {
        $this->firstname = $firstname;
    }

    public function setSecondName(string $secondname)
    {
        $this->secondname = $secondname;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }
    public function setAge(int $age)
    {
        $this->age = $age;
    }

    public function getLastName(): string
    {
        return $this->lastname;
    }

    public function getFirstName(): string
    {
        return $this->firstname;
    }

    public function getSecondName(): string
    {
        return $this->secondname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getAge(): int
    {
        return $this->age;
    }
}