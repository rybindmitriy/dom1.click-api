<?php

declare(strict_types=1);

namespace App\UseCase\CExchange;

use App\Dto\Response\CExchange\GetCExchangeStatusResponse;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

interface GetCExchangeStatusInterface
{
    /**
     * @throws NonUniqueResultException
     * @throws NotFoundHttpException
     */
    public function execute(string $cExchangeId): GetCExchangeStatusResponse;
}
