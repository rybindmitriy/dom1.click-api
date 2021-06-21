<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\CExchange;
use App\Entity\CExchangeStatusEnum;
use App\Message\UnloadDataTo1CMessage;
use App\Repository\CExchangeRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use function Sentry\captureException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Throwable;

final class UnloadDataTo1CMessageHandler implements MessageHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CExchangeRepositoryInterface $cExchangeRepository,
    ) {
    }

    public function __invoke(UnloadDataTo1CMessage $message)
    {
        /** @var CExchange|null $cExchange */
        $cExchange = null;

        try {
            $cExchange = $this->cExchangeRepository->findOneByIdWithBuilding($message->getCExchangeId());

            $cExchange->setStatus(CExchangeStatusEnum::unloading());

            $this->entityManager->flush();

            // @todo

            $cExchange->setStatus(CExchangeStatusEnum::success());

            $this->entityManager->flush();
        } catch (Throwable $exception) {
            captureException($exception);

            if (null !== $cExchange) {
                $cExchange->setStatus(CExchangeStatusEnum::error());

                $this->entityManager->flush();
            }
        }
    }
}
