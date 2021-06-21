<?php

declare(strict_types=1);

namespace App\Service\Building;

use App\Dto\Request\Building\CreateBuildingRequest;
use App\Entity\Building;
use App\Repository\OrganizationRepositoryInterface;
use App\UseCase\Building\CreateBuildingInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

final class CreateBuildingService implements CreateBuildingInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private OrganizationRepositoryInterface $organizationRepository
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function execute(CreateBuildingRequest $createBuildingRequest): void
    {
        $organization = $this->organizationRepository->findOneById($createBuildingRequest->organizationId);

        $building =
            new Building(
                $createBuildingRequest->address,
                $createBuildingRequest->fiasId,
                Uuid::v4(),
                $organization,
                $createBuildingRequest->timeOffset,
            );

        $this->entityManager->persist($building);

        $this->entityManager->flush();
    }
}
