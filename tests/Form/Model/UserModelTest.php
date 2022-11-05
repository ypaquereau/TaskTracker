<?php

declare(strict_types=1);

namespace App\Tests\Form\Model;

use App\DataFixtures\UserFixtures;
use App\Form\Model\UserModel;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
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
        $validator = self::getContainer()->get(ValidatorInterface::class);
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

    /**
     * @throws Exception
     */
    public function testUniqueEmail(): void
    {
        /**
         * @var AbstractDatabaseTool $databaseToolCollection
         * @phpstan-ignore-next-line
         */
        $databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $databaseTool->loadFixtures([UserFixtures::class]);

        $userModel = $this->getUserModel();
        $userModel->setEmail('test@test.com');

        $errors = $this->validator->validate($userModel);
        $this->assertEquals(1, $errors->count());

        $mailError = $errors->get(0);
        $this->assertEquals('email', $mailError->getPropertyPath());
        $this->assertEquals('Email "test@test.com" is already exists', $mailError->getMessage());
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
