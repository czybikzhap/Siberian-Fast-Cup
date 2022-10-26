<?php
/**
 * @Entity @Table(name = "users")
 **/
class User{
    /** @Id @Column(type = "integer")  @GeneratedValue **/
    protected $id;
    /** @Column(type = "string") **/
    protected $name;
    /** @Column(type = "string") **/
    protected $email;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

}