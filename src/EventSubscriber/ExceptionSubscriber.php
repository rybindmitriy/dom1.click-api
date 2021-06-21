<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\BadRequestValidationHttpException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                'onKernelException',
            ],
        ];
    }

    public function onKernelException(ExceptionEvent $exceptionEvent): void
    {
        $exception = $exceptionEvent->getThrowable();

        if ($exception instanceof BadRequestValidationHttpException) {
            $response =
                new JsonResponse(
                    [
                        'errors' => $exception->getErrors(),
                    ],
                    $exception->getStatusCode(),
                );

            $exceptionEvent->setResponse($response);
        }
    }
}
