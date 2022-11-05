<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use App\Form\Model\UserModel;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    private const EMAIL = "test@test.com";
    private const PASSWORD = "password";
    private const ROLES = ['ADMIN'];
    private const FIRST_NAME = 'firstname';
    private const LAST_NAME = 'lastname';


    public function testEmail(): void
    {
        $user = new User();
        $user->setEmail(self::EMAIL);

        $this->assertEquals(self::EMAIL, $user->getEmail());
    }

    public function testUserIdentifier(): void
    {
        $user = new User();
        $user->setEmail(self::EMAIL);

        $this->assertEquals(self::EMAIL, $user->getUserIdentifier());
    }

    public function testRoles(): void
    {
        $user = new User();
        $user->setRoles(self::ROLES);

        $this->assertEquals([...self::ROLES, 'ROLE_USER'], $user->getRoles());
    }

    public function testEmptyRoles(): void
    {
        $user = new User();
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testPassword(): void
    {
        $user = new User();
        $user->setPassword(self::PASSWORD);

        $this->assertEquals(self::PASSWORD, $user->getPassword());
    }

    public function testPlainPassword(): void
    {
        $user = new User();
        $user->setPlainPassword(self::PASSWORD);

        $this->assertEquals(self::PASSWORD, $user->getPlainPassword());
    }

    public function testEraseCredentials(): void
    {
        $user = new User();
        $user->setPlainPassword(self::PASSWORD);
        $user->eraseCredentials();

        $this->assertEquals(null, $user->getPlainPassword());
    }

    public function testFirstName(): void
    {
        $user = new User();
        $user->setFirstName(self::FIRST_NAME);

        $this->assertEquals(self::FIRST_NAME, $user->getFirstName());
    }

    public function testLastName(): void
    {
        $user = new User();
        $user->setLastName(self::LAST_NAME);

        $this->assertEquals(self::LAST_NAME, $user->getLastName());
    }

    public function testCreate(): void
    {
        $userModel = new UserModel();
        $userModel->setEmail(self::EMAIL);
        $userModel->setPassword(self::PASSWORD);
        $userModel->setFirstName(self::FIRST_NAME);
        $userModel->setLastName(self::LAST_NAME);

        $user = User::create($userModel);

        $this->assertEquals(null, $user->getId());
        $this->assertEquals(self::EMAIL, $user->getEmail());
        $this->assertEquals(self::EMAIL, $user->getUserIdentifier());
        $this->assertEquals(null, $user->getPassword());
        $this->assertEquals(self::PASSWORD, $user->getPlainPassword());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertEquals(self::FIRST_NAME, $user->getFirstName());
        $this->assertEquals(self::LAST_NAME, $user->getLastName());
    }
}
