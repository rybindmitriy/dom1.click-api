<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="rooms_accounts",
 *     indexes={
 *          @Index(columns={"updated_at"})
 *     },
 *     uniqueConstraints={
 *          @UniqueConstraint(columns={"room_id", "external_id"})
 *     }
 * )
 */
class RoomAccount extends AbstractEntity
{
    /**
     * @ORM\Column(type="uuid")
     */
    private Uuid $externalId;

    /**
     * @ORM\OneToMany(targetEntity=Meter::class, mappedBy="roomAccount")
     *
     * @psalm-var Collection<int, Meter>
     */
    private Collection $meters;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="roomAccounts")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private Room $room;

    /**
     * @ORM\Column(type="integer")
     */
    private int $status;

    public function __construct(Uuid $externalId, Uuid $id, Room $room, RoomAccountStatusEnum $status)
    {
        parent::__construct($id);

        /** @var int $statusValue */
        $statusValue = $status->value;

        $this->externalId = $externalId;
        $this->meters     = new ArrayCollection();
        $this->room       = $room;
        $this->status     = $statusValue;
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

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(RoomAccountStatusEnum $status): void
    {
        /** @var int $statusValue */
        $statusValue = $status->value;

        $this->status = $statusValue;
    }
}
