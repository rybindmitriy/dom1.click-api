<?php

declare(strict_types=1);

namespace App\Dto\CExchange\Part\Room;

use App\Dto\CExchange\Part\Room\Account\Meter;
use JMS\Serializer\Annotation as Serializer;

/**
 * @psalm-suppress MissingConstructor
 */
final class Account
{
    public string $id;

    /**
     * @Serializer\SerializedName("isActive")
     */
    public bool $isActive;

    /**
     * @Serializer\Type("array<App\Dto\CExchange\Part\Room\Account\Meter>")
     *
     * @var Meter[]
     */
    public array $meters;
}
