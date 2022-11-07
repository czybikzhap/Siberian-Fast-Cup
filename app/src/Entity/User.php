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
    #[Column(type: 'string', nullable: false)]
    protected string $lastname;
    #[Column(type: 'string', nullable: false)]
    protected ?string  $firstname;
    #[Column(type: 'string', nullable: false)]
    protected ?string $secondname;
    #[Column(type: 'string', unique: true, nullable: false)]
    protected string $email;
    #[Column(type: 'string', nullable: false)]
    protected string $password;
    #[Column(type: 'string', unique: true, nullable: false)]
    protected ?string $phone;
    #[Column(type: 'integer', nullable: false)]
    protected ?int $age;
    #[Column(type: 'string', unique: true, nullable: false)]
    protected ?string $token;

    public function __construct(
        string $lastname,
        string $email,
        string $password,
        string $firstname = null,
        string $secondname = null,
        string $phone = null,
        int $age = null,
        string $token = null)
    {
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->secondname = $secondname;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->age = $age;
        $this->token = $token;
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

    public function setToken(string $token)
    {
        $this->token = $token;
    }

    public  function getId(): int
    {
        return $this->id;
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

    public function getToken(): ?string
    {
       return $this->token;
    }

//    public function toArray(): array
//    {
//        return [
//            'id' => $this->getId(),
//            'lastsname'=>$this->getLastName(),
//            'firstsname'=>$this->getFirstName(),
//            'secondname'=>$this->getSecondName(),
//            'email'=>$this->getEmail(),
//            'password'=>$this->getPassword(),
//            'phone'=>$this->getPhone(),
//            'age'=>$this->getAge()
//        ];
//    }
}