<?php

declare(strict_types=1);

namespace App\Controller\CExchange;

use App\UseCase\CExchange\StartCExchangeInterface;
use InvalidArgumentException;
use function Sentry\captureException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class StartActionController
{
    /**
     * @throws InvalidArgumentException
     */
    #[Route('/start', name: 'start', methods: [Request::METHOD_POST])]
    public function __invoke(
        Request $request,
        StartCExchangeInterface $startCExchangeHandler
    ): JsonResponse {
        $response = new JsonResponse();

        try {
            $startCExchangeResponse = $startCExchangeHandler->execute($request);

            $response->setData($startCExchangeResponse);
        } catch (BadRequestHttpException) {
            $response->setStatusCode(Response::HTTP_BAD_REQUEST);
        } catch (Throwable $exception) {
            captureException($exception);

            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
