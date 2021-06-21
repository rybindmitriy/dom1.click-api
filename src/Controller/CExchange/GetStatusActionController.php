<?php

declare(strict_types=1);

namespace App\Controller\CExchange;

use App\UseCase\CExchange\GetCExchangeStatusInterface;
use InvalidArgumentException;
use function Sentry\captureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class GetStatusActionController
{
    /**
     * @throws InvalidArgumentException
     */
    #[Route('/{id}/get-status', name: 'getStatus', methods: [Request::METHOD_GET])]
    public function __invoke(GetCExchangeStatusInterface $getCExchangeStatusHandler, string $id): JsonResponse
    {
        $response = new JsonResponse();

        try {
            $getCExchangeStatusResponse = $getCExchangeStatusHandler->execute($id);

            $response->setData($getCExchangeStatusResponse);
        } catch (NotFoundHttpException) {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
        } catch (Throwable $exception) {
            captureException($exception);

            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
