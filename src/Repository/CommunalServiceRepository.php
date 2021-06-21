<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CommunalService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class CommunalServiceRepository extends ServiceEntityRepository implements CommunalServiceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommunalService::class);
    }
}
