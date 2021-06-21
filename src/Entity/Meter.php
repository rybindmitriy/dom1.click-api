<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\UniqueConstraint;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="meters",
 *     indexes={
 *          @Index(columns={"updated_at"})
 *     },
 *     uniqueConstraints={
 *          @UniqueConstraint(columns={"building_communal_service_id", "external_id"})
 *     }
 * )
 */
class Meter extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity=BuildingCommunalService::class, inversedBy="meters")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private BuildingCommunalService $buildingCommunalService;

    /**
     * @ORM\Column(type="uuid")
     */
    private Uuid $externalId;

    /**
     * @ORM\OneToMany(targetEntity=MeterIndication::class, mappedBy="meter")
     *
     * @psalm-var Collection<int, MeterIndication>
     */
    private Collection $indications;

    /**
     * @ORM\Column(type="integer")
     */
    private int $indicationsCount;

    /**
     * @ORM\ManyToOne(targetEntity=RoomAccount::class, inversedBy="meters")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private RoomAccount $roomAccount;

    /**
     * @ORM\Column(type="integer")
     */
    private int $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $title;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(
        BuildingCommunalService $buildingCommunalService,
        Uuid $externalId,
        Uuid $id,
        int $indicationsCount,
        RoomAccount $roomAccount,
        MeterStatusEnum $status,
        ?string $title
    ) {
        Assert::greaterThan($indicationsCount, 0, 'Тарифность счётчика не может быть меньше 1');

        parent::__construct($id);

        /** @var int $statusValue */
        $statusValue = $status->value;

        $this->buildingCommunalService = $buildingCommunalService;
        $this->externalId              = $externalId;
        $this->indications             = new ArrayCollection();
        $this->indicationsCount        = $indicationsCount;
        $this->roomAccount             = $roomAccount;
        $this->status                  = $statusValue;
        $this->title                   = $title;
    }

    public function getBuildingCommunalService(): BuildingCommunalService
    {
        return $this->buildingCommunalService;
    }

    public function getExternalId(): Uuid
    {
        return $this->externalId;
    }

    /**
     * @psalm-return Collection<int, MeterIndication>
     *
     * @return Collection|MeterIndication[]
     */
    public function getIndications(): Collection
    {
        return $this->indications;
    }

    public function getIndicationsCount(): int
    {
        return $this->indicationsCount;
    }

    public function getRoomAccount(): RoomAccount
    {
        return $this->roomAccount;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setBuildingCommunalService(BuildingCommunalService $buildingCommunalService): void
    {
        $this->buildingCommunalService = $buildingCommunalService;
    }

    public function setIndicationsCount(int $indicationsCount): void
    {
        $this->indicationsCount = $indicationsCount;
    }

    public function setStatus(MeterStatusEnum $status): void
    {
        /** @var int $statusValue */
        $statusValue = $status->value;

        $this->status = $statusValue;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }
}
