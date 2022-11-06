<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form;

use App\Form\Model\UserModel;
use App\Form\RegistrationType;
use Symfony\Component\Form\Test\TypeTestCase;

final class RegistrationTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $email = 'test@test.com';
        $firstName = 'first';
        $lastName = 'last';
        $password = 'password';

        $formData = [
            'email' => $email,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'password' => $password
        ];

        $userModel = new UserModel();

        $form = $this->factory->create(RegistrationType::class, $userModel);

        $expectedModel = new UserModel();
        $expectedModel->setEmail($email);
        $expectedModel->setFirstName($firstName);
        $expectedModel->setLastName($lastName);
        $expectedModel->setPassword($password);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expectedModel, $userModel);
    }
}
