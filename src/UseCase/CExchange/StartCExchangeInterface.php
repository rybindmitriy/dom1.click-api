<?php

declare(strict_types=1);

namespace App\UseCase\CExchange;

use App\Dto\Response\CExchange\StartCExchangeResponse;
use LogicException;
use RuntimeException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

interface StartCExchangeInterface
{
    /**
     * @throws IOException
     * @throws LogicException
     * @throws RuntimeException
     * @throws Throwable
     */
    public function execute(Request $request): StartCExchangeResponse;
}
