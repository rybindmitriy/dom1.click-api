<?php

declare(strict_types=1);

namespace App\Dto\Response\CExchange;

use App\Entity\CExchange;
use JMS\Serializer\Annotation as Serializer;
use JsonSerializable;

/**
 * @psalm-suppress MissingConstructor
 */
final class StartCExchangeResponse implements JsonSerializable
{
    /**
     * @Serializer\SerializedName("cExchangeId")
     */
    private string $cExchangeId;

    public function __construct(CExchange $cExchange)
    {
        $this->cExchangeId = $cExchange->getId()->toRfc4122();
    }

    public function getCExchangeId(): string
    {
        return $this->cExchangeId;
    }

    public function jsonSerialize(): array
    {
        return [
            'cExchangeId' => $this->getCExchangeId(),
        ];
    }
}
