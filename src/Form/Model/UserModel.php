<?php

namespace App\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class UserModel
{
    #[Assert\Email]
    private string $email;

    #[Assert\NotBlank]
    private string $password;

    #[Assert\NotBlank]
    private string $firstName;

    #[Assert\NotBlank]
    private string $lastName;

    public function __construct()
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }
}
