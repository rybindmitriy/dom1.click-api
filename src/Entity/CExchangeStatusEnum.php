<?php

declare(strict_types=1);

namespace App\Entity;

use Spatie\Enum\Enum;

/**
 * @method static self error()
 * @method static self started()
 * @method static self success()
 * @method static self unloading()
 * @method static self uploading()
 */
final class CExchangeStatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'error'     => 0,
            'started'   => 1,
            'success'   => 2,
            'unloading' => 3,
            'uploading' => 4,
        ];
    }
}
