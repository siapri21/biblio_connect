<?php

namespace App\Service;

use App\Entity\ActivityLog;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ActivityLogger
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function log(?User $user, string $action, string $entityType, ?int $entityId = null, ?string $details = null): void
    {
        $log = (new ActivityLog())
            ->setUser($user)
            ->setAction($action)
            ->setEntityType($entityType)
            ->setEntityId($entityId)
            ->setDetails($details)
            ->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($log);
    }
}
