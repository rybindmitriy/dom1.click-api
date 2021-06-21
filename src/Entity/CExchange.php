<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     indexes={
 *          @Index(columns={"building_id", "created_at"})
 *     },
 *     name="c_exchanges"
 * )
 */
class CExchange extends AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity=Building::class, inversedBy="cExchanges")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private Building $building;

    /**
     * @ORM\Column(type="integer")
     */
    private int $status;

    public function __construct(Building $building, Uuid $id)
    {
        parent::__construct($id);

        /** @var int $statusValue */
        $statusValue = CExchangeStatusEnum::started()->value;

        $this->building = $building;
        $this->status   = $statusValue;
    }

    public function getBuilding(): Building
    {
        return $this->building;
    }

    public function getStatus(): CExchangeStatusEnum
    {
        return CExchangeStatusEnum::from($this->status);
    }

    public function isCompleted(): bool
    {
        $status = $this->getStatus();

        return
            $status->equals(CExchangeStatusEnum::error())
            || $status->equals(CExchangeStatusEnum::success());
    }

    public function setStatus(CExchangeStatusEnum $status): void
    {
        /** @var int $statusValue */
        $statusValue = $status->value;

        $this->status = $statusValue;
    }
}
