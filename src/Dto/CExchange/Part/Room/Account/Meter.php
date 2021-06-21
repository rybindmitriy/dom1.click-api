<?php

declare(strict_types=1);

namespace App\Dto\CExchange\Part\Room\Account;

use App\Dto\CExchange\Part\Room\Account\Meter\CommunalService;
use App\Dto\CExchange\Part\Room\Account\Meter\LastIndication;
use JMS\Serializer\Annotation as Serializer;

/**
 * @psalm-suppress MissingConstructor
 */
final class Meter
{
    /**
     * @Serializer\SerializedName("communalService")
     */
    public CommunalService $communalService;

    public string $id;

    /**
     * @Serializer\SerializedName("indicationsCount")
     */
    public int $indicationsCount;

    /**
     * @Serializer\SerializedName("isActive")
     */
    public bool $isActive;

    /**
     * @Serializer\SerializedName("lastIndication")
     */
    public ?LastIndication $lastIndication = null;

    private ?string $title;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = trim($title);

        if ('' === $this->title) {
            $this->title = null;
        }
    }
}
