<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(
 *     name="meters_indications",
 *     uniqueConstraints={
 *          @UniqueConstraint(columns={"meter_id", "year_of_the_period", "month_of_the_period"})
 *     }
 * )
 */
class MeterIndication extends AbstractEntity
{
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $dayIndication;

    /**
     * @ORM\ManyToOne(targetEntity=Meter::class, inversedBy="indications")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private Meter $meter;

    /**
     * @ORM\Column(type="integer")
     */
    private int $monthOfThePeriod;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $nightIndication;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $peakIndication;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private DateTimeImmutable $period;

    /**
     * @ORM\Column(type="integer")
     */
    private int $yearOfThePeriod;

    public function __construct(
        ?float $dayIndication,
        Uuid $id,
        Meter $meter,
        ?float $nightIndication,
        ?float $peakIndication,
        DateTimeImmutable $period,
    ) {
        parent::__construct($id);

        $this->dayIndication    = $dayIndication;
        $this->meter            = $meter;
        $this->monthOfThePeriod = (int) $period->format('n');
        $this->nightIndication  = $nightIndication;
        $this->peakIndication   = $peakIndication;
        $this->period           = $period;
        $this->yearOfThePeriod  = (int) $period->format('Y');
    }

    public function getDayIndication(): ?float
    {
        return $this->dayIndication;
    }

    public function getMeter(): Meter
    {
        return $this->meter;
    }

    public function getMonthOfThePeriod(): int
    {
        return $this->monthOfThePeriod;
    }

    public function getNightIndication(): ?float
    {
        return $this->nightIndication;
    }

    public function getPeakIndication(): ?float
    {
        return $this->peakIndication;
    }

    public function getPeriod(): DateTimeImmutable
    {
        return $this->period;
    }

    public function getYearOfThePeriod(): int
    {
        return $this->yearOfThePeriod;
    }
}
