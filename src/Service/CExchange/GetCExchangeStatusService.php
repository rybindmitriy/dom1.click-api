<?php

declare(strict_types=1);

namespace App\Service\CExchange;

use App\Dto\Response\CExchange\GetCExchangeStatusResponse;
use App\Repository\CExchangeRepositoryInterface;
use App\UseCase\CExchange\GetCExchangeStatusInterface;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class GetCExchangeStatusService implements GetCExchangeStatusInterface
{
    public function __construct(private CExchangeRepositoryInterface $cExchangeRepository)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function execute(string $cExchangeId): GetCExchangeStatusResponse
    {
        try {
            $cExchange = $this->cExchangeRepository->findOneById($cExchangeId);
        } catch (NoResultException) {
            throw new NotFoundHttpException();
        }

        return new GetCExchangeStatusResponse($cExchange);
    }
}
