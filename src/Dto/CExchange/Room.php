<?php

declare(strict_types=1);

namespace App\Dto\CExchange;

use App\Dto\CExchange\Part\Room\Account;
use JMS\Serializer\Annotation as Serializer;

/**
 * @psalm-suppress MissingConstructor
 */
final class Room
{
    /**
     * @Serializer\Type("array<App\Dto\CExchange\Part\Room\Account>")
     *
     * @var Account[]
     */
    public array $accounts;

    public int $code;

    public string $id;

    public string $suffix;
}
