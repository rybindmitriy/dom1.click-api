<?php

declare(strict_types=1);

namespace App\Dto\CExchange\Part\Room\Account\Meter;

/**
 * @psalm-suppress MissingConstructor
 */
final class CommunalService
{
    public string $id;

    private string $title;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = trim($title);
    }
}
