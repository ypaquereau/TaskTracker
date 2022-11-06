<?php

namespace App\Tests\Unit\Form;

use App\Form\LoginType;
use Generator;
use Symfony\Component\Form\Test\TypeTestCase;

class LoginTypeTest extends TypeTestCase
{
    /**
     * @dataProvider provideFormData
     */
    public function testSubmitValidData(bool $rememberMe): void
    {
        $email = 'test@test.com';
        $password = 'password';

        $formData = [
            'email' => $email,
            'password' => $password,
            '_remember_me' => $rememberMe
        ];

        $form = $this->factory->create(LoginType::class);

        $form->submit($formData);

        $this->assertTrue($form->isValid() && $form->isSynchronized());
        $this->assertEquals($formData, $form->getData());
    }

    /** @phpstan-ignore-next-line */
    private function provideFormData(): Generator
    {
        yield 'with remember me' => [true];
        yield 'without remember me' => [false];
    }
}
