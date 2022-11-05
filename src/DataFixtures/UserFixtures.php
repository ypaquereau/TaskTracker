<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setFirstName('Test')
            ->setLastName('Test')
            ->setEmail('test@test.com')
            ->setPlainPassword('password')
        ;

        $manager->persist($user);
        $manager->flush();
    }
}
