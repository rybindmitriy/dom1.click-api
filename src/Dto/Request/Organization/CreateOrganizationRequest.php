<?php

declare(strict_types=1);

namespace App\Dto\Request\Organization;

use App\Dto\Request\RequestInterface;

/**
 * @psalm-suppress MissingConstructor
 */
final class CreateOrganizationRequest implements RequestInterface
{
    public string $cExchangeToken;

    public string $inn;

    public string $title;
}
