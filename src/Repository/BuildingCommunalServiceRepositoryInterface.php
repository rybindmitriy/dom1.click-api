<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use App\Entity\BuildingCommunalService;

interface BuildingCommunalServiceRepositoryInterface
{
    /**
     * @return BuildingCommunalService[]
     */
    public function findByBuildingWithCommunalServices(Building $building): array;
}
