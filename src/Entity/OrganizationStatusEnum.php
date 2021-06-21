<?php

declare(strict_types=1);

namespace App\Entity;

use Spatie\Enum\Enum;

/**
 * @method static self active()
 * @method static self inactive()
 */
final class OrganizationStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'active'   => 1,
            'inactive' => 0,
        ];
    }
}
