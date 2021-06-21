<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Organization;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

interface OrganizationRepositoryInterface
{
    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findOneById(string $id): Organization;
}
