<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Building;
use App\Repository\BuildingRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Throwable;

final class BuildingProvider implements UserProviderInterface
{
    public function __construct(private BuildingRepositoryInterface $buildingRepository)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        try {
            return $this->buildingRepository->findOneByFiasIdWithOrganization($identifier);
        } catch (Throwable) {
            throw new UserNotFoundException('Здание не найдено');
        }
    }

    /**
     * @deprecated
     *
     * @throws UserNotFoundException
     */
    public function loadUserByUsername(string $username): UserInterface
    {
        try {
            return $this->buildingRepository->findOneByFiasIdWithOrganization($username);
        } catch (Throwable) {
            throw new UserNotFoundException('Здание не найдено');
        }
    }

    public function refreshUser(UserInterface $user): void
    {
    }

    public function supportsClass(string $class): bool
    {
        return Building::class === $class;
    }
}
