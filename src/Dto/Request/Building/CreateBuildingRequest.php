<?php

declare(strict_types=1);

namespace App\Dto\Request\Building;

use App\Dto\Request\RequestInterface;
use JMS\Serializer\Annotation as Serializer;

/**
 * @psalm-suppress MissingConstructor
 */
final class CreateBuildingRequest implements RequestInterface
{
    public string $address;

    /**
     * @Serializer\SerializedName("fiasId")
     */
    public string $fiasId;

    /**
     * @Serializer\SerializedName("organizationId")
     */
    public string $organizationId;

    /**
     * @Serializer\SerializedName("timeOffset")
     */
    public string $timeOffset;
}
