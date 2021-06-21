<?php

declare(strict_types=1);

namespace App\Message;

use App\Entity\CExchange;

final class UnloadDataTo1CMessage implements LockableMessageInterface
{
    private string $cExchangeId;

    public function __construct(CExchange $cExchange)
    {
        $this->cExchangeId = $cExchange->getId()->toRfc4122();
    }

    public function getCExchangeId(): string
    {
        return $this->cExchangeId;
    }

    public function getLockKey(): string
    {
        return __CLASS__."#{$this->cExchangeId}";
    }
}
