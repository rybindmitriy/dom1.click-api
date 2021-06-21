<?php

declare(strict_types=1);

namespace App\UseCase\Building;

use App\Dto\Request\Building\CreateBuildingRequest;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use InvalidArgumentException;

interface CreateBuildingInterface
{
    /**
     * @throws InvalidArgumentException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function execute(CreateBuildingRequest $createBuildingRequest): void;
}
