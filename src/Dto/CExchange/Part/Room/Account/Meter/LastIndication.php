<?php

declare(strict_types=1);

namespace App\Dto\CExchange\Part\Room\Account\Meter;

use JMS\Serializer\Annotation as Serializer;

/**
 * @psalm-suppress MissingConstructor
 */
final class LastIndication
{
    public string $period;

    /**
     * @Serializer\SerializedName("dayIndication")
     */
    private ?float $dayIndication;

    /**
     * @Serializer\SerializedName("nightIndication")
     */
    private ?float $nightIndication;

    /**
     * @Serializer\SerializedName("peakIndication")
     */
    private ?float $peakIndication;

    public function getDayIndication(): ?float
    {
        return $this->dayIndication;
    }

    public function getNightIndication(): ?float
    {
        return $this->nightIndication;
    }

    public function getPeakIndication(): ?float
    {
        return $this->peakIndication;
    }

    public function setDayIndication(float | string $dayIndication): void
    {
        $this->dayIndication = '' !== $dayIndication ? (float) $dayIndication : null;
    }

    public function setNightIndication(float | string $nightIndication): void
    {
        $this->nightIndication = '' !== $nightIndication ? (float) $nightIndication : null;
    }

    public function setPeakIndication(float | string $peakIndication): void
    {
        $this->peakIndication = '' !== $peakIndication ? (float) $peakIndication : null;
    }
}
