<?php

declare(strict_types=1);

namespace App\Service\Organization;

use App\Dto\Request\Organization\CreateOrganizationRequest;
use App\Dto\Response\Organization\CreateOrganizationResponse;
use App\Entity\Organization;
use App\UseCase\Organization\CreateOrganizationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

final class CreateOrganizationService implements CreateOrganizationInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function execute(CreateOrganizationRequest $createOrganizationRequest): CreateOrganizationResponse
    {
        $organization =
            new Organization(
                Uuid::v4(),
                $createOrganizationRequest->inn,
                $createOrganizationRequest->title
            );

        $this->entityManager->persist($organization);

        $this->entityManager->flush();

        $createOrganizationResponse                 = new CreateOrganizationResponse();
        $createOrganizationResponse->cExchangeToken = $organization->getCExchangeToken();

        return $createOrganizationResponse;
    }
}
