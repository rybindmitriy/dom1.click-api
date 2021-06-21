<?php

declare(strict_types=1);

namespace App\Dto\Response\CExchange;

use App\Entity\CExchange;
use JsonSerializable;

final class GetCExchangeStatusResponse implements JsonSerializable
{
    private int $status;

    public function __construct(CExchange $cExchange)
    {
        /** @var int $statusValue */
        $statusValue = $cExchange->getStatus()->value;

        $this->status = $statusValue;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function jsonSerialize(): array
    {
        return [
            'status' => $this->getStatus(),
        ];
    }
}
