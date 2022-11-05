<?php

declare(strict_types=1);

namespace App\Tests\Functional\Entity;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserTest extends KernelTestCase
{
    private AbstractDatabaseTool $databaseTool;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        /**
         * @var DatabaseToolCollection $databaseToolCollection
         * @phpstan-ignore-next-line
         */
        $databaseToolCollection = self::getContainer()->get(DatabaseToolCollection::class);

        $this->databaseTool = $databaseToolCollection->get();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $this->entityManager = $entityManager;

        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);

        $this->passwordHasher = $passwordHasher;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }

    public function testPrePersistPassword(): void
    {
        $password = 'password';

        $user = new User();
        $user
            ->setFirstName('Test')
            ->setLastName('Test')
            ->setEmail('prepersist@test.com')
            ->setPlainPassword($password)
        ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->assertNull($user->getPlainPassword());
        $this->assertTrue($this->passwordHasher->isPasswordValid($user, $password));
    }

    public function testPreUpdatePassword(): void
    {
        $password = 'new_password';

        $this->databaseTool->loadFixtures([UserFixtures::class]);

        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'test@test.com']);

        $this->assertNotTrue($this->passwordHasher->isPasswordValid($user, $password));

        $user->setPlainPassword($password);
        $this->entityManager->flush();

        $this->assertTrue($this->passwordHasher->isPasswordValid($user, $password));
    }
}
