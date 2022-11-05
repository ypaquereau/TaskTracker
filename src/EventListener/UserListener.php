<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(event: Events::preFlush, entity: User::class)]
final class UserListener
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function __invoke(User $user): void
    {
        if ($user->getPlainPassword()) {
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $user->getPlainPassword()
                )
            );

            $user->eraseCredentials();
        }
    }
}
