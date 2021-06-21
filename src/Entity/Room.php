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
 *     name="rooms",
 *     uniqueConstraints={
 *          @UniqueConstraint(columns={"building_id", "external_id"})
 *     }
 * )
 */
class Room extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity=Building::class, inversedBy="rooms")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private Building $building;

    /**
     * @ORM\Column(type="integer")
     */
    private int $code;

    /**
     * @ORM\Column(type="uuid")
     */
    private Uuid $externalId;

    /**
     * @ORM\OneToMany(targetEntity=RoomAccount::class, mappedBy="room")
     *
     * @psalm-var Collection<int, RoomAccount>
     */
    private Collection $roomAccounts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $suffix = null;

    public function __construct(
        Building $building,
        int $code,
        Uuid $externalId,
        Uuid $id,
        string $suffix,
    ) {
        parent::__construct($id);

        $this->building     = $building;
        $this->code         = $code;
        $this->externalId   = $externalId;
        $this->roomAccounts = new ArrayCollection();

        $this->setSuffix($suffix);
    }

    public function getBuilding(): Building
    {
        return $this->building;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getExternalId(): Uuid
    {
        return $this->externalId;
    }

    /**
     * @psalm-return Collection<int, RoomAccount>
     *
     * @return Collection|RoomAccount[]
     */
    public function getRoomAccounts(): Collection
    {
        return $this->roomAccounts;
    }

    public function getSuffix(): ?string
    {
        return $this->suffix;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function setSuffix(string $suffix): void
    {
        $suffix = trim($suffix);

        $this->suffix = false === empty($suffix) ? $suffix : null;
    }
}
