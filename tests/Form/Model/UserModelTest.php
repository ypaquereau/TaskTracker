<?php

declare(strict_types=1);

namespace App\Tests\Form\Model;

use App\Form\Model\UserModel;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserModelTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $validator = self::getContainer()->get('validator');
        $this->validator = $validator;

        self::bootKernel();
    }

    public function testValid(): void
    {
        $userModel = $this->getUserModel();

        $errors = $this->validator->validate($userModel);
        $this->assertCount(0, $errors);
    }

    public function testInvalidEmail(): void
    {
        $userModel = $this->getUserModel();
        $userModel->setEmail('invalid_email');

        $errors = $this->validator->validate($userModel);
        $this->assertEquals(1, $errors->count());

        $mailError = $errors->get(0);
        $this->assertEquals('email', $mailError->getPropertyPath());
        $this->assertEquals('This value is not a valid email address.', $mailError->getMessage());
    }

    private function getUserModel(): UserModel
    {
        $userModel = new UserModel();

        $userModel->setEmail('user@test.com');
        $userModel->setPassword('password');
        $userModel->setFirstName('firstname');
        $userModel->setLastName('lastname');

        return $userModel;
    }
}
