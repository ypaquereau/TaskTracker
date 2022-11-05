<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsEntityListener(entity: User::class)]
final class UserListener
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function prePersist(User $user): void
    {
        $this->encodePassword($user);
    }

    public function preUpdate(User $user, LifecycleEventArgs $args): void
    {
        $this->encodePassword($user);

        // necessary to force the update to see the change
        $em = $args->getObjectManager();
        $meta = $em->getClassMetadata(get_class($user));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $user);
    }

    private function encodePassword(User $user): void
    {
        if ($user->getPlainPassword()) {
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $user->getPlainPassword()
                )
            );
        }
    }
}
