<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="buildings_communal_services",
 *     uniqueConstraints={
 *          @UniqueConstraint(columns={"building_id", "communal_service_id"})
 *     }
 * )
 */
class BuildingCommunalService extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity=Building::class, inversedBy="buildingCommunalServices")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private Building $building;

    /**
     * @ORM\ManyToOne(targetEntity=CommunalService::class, inversedBy="buildingCommunalServices")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private CommunalService $communalService;

    /**
     * @ORM\Column(type="uuid")
     */
    private Uuid $externalId;

    /**
     * @ORM\OneToMany(targetEntity=Meter::class, mappedBy="buildingCommunalService")
     *
     * @psalm-var Collection<int, Meter>
     */
    private Collection $meters;

    public function __construct(
        Building $building,
        CommunalService $communalService,
        Uuid $externalId,
        Uuid $id,
    ) {
        parent::__construct($id);

        $this->building        = $building;
        $this->communalService = $communalService;
        $this->externalId      = $externalId;
        $this->meters          = new ArrayCollection();
    }

    public function getBuilding(): Building
    {
        return $this->building;
    }

    public function getCommunalService(): CommunalService
    {
        return $this->communalService;
    }

    public function getExternalId(): Uuid
    {
        return $this->externalId;
    }

    /**
     * @psalm-return Collection<int, Meter>
     *
     * @return Collection|Meter[]
     */
    public function getMeters(): Collection
    {
        return $this->meters;
    }

    public function setCommunalService(CommunalService $communalService): void
    {
        $this->communalService = $communalService;
    }
}
