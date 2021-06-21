<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Dto\CExchange\Part\Room\Account;
use App\Dto\CExchange\Room;
use App\Entity\Building;
use App\Entity\BuildingCommunalService;
use App\Entity\CExchange;
use App\Entity\CExchangeStatusEnum;
use App\Entity\CommunalService;
use App\Entity\Meter;
use App\Entity\MeterIndication;
use App\Entity\MeterStatusEnum;
use App\Entity\RoomAccount;
use App\Entity\RoomAccountStatusEnum;
use App\Message\UnloadDataTo1CMessage;
use App\Message\UploadReceivedFrom1CDataMessage;
use App\Repository\BuildingCommunalServiceRepositoryInterface;
use App\Repository\CExchangeRepositoryInterface;
use App\Repository\MeterIndicationRepositoryInterface;
use App\Repository\MeterRepositoryInterface;
use App\Repository\RoomAccountRepositoryInterface;
use App\Repository\RoomRepositoryInterface;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Generator;
use InvalidArgumentException;
use JMS\Serializer\ArrayTransformerInterface;
use JsonException;
use function Sentry\captureException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;
use Throwable;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class UploadReceivedFrom1CDataMessageHandler implements MessageHandlerInterface
{
    private Building $building;

    /** @var BuildingCommunalService[] */
    private array $buildingCommunalServices = [];

    public function __construct(
        private ArrayTransformerInterface $arrayTransformer,
        private BuildingCommunalServiceRepositoryInterface $buildingCommunalServiceRepository,
        private CExchangeRepositoryInterface $cExchangeRepository,
        private string $cExchangesRootDir,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus,
        private MeterIndicationRepositoryInterface $meterIndicationRepository,
        private MeterRepositoryInterface $meterRepository,
        private RoomAccountRepositoryInterface $roomAccountRepository,
        private RoomRepositoryInterface $roomRepository,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(UploadReceivedFrom1CDataMessage $message)
    {
        /** @var CExchange|null $cExchange */
        $cExchange = null;

        $cExchangeDir = null;

        try {
            $cExchange = $this->cExchangeRepository->findOneByIdWithBuilding($message->getCExchangeId());

            $cExchange->setStatus(CExchangeStatusEnum::uploading());

            $this->entityManager->flush();

            $this->building = $cExchange->getBuilding();

            $this->buildingCommunalServices =
                $this->buildingCommunalServiceRepository->findByBuildingWithCommunalServices($this->building);

            $cExchangeDir = "{$this->cExchangesRootDir}/{$cExchange->getId()->toRfc4122()}";

            $fileContents =
                file_get_contents(glob("{$cExchangeDir}/__rooms__*.json", GLOB_NOSORT)[0]);
            $fileContents = preg_replace('~\x{FEFF}~u', '', $fileContents);
            $fileContents = trim($fileContents);

            /** @var array $roomAsArr */
            foreach ($this->getNextRoom($fileContents) as $roomAsArr) {
                /** @var Room $roomDTO */
                $roomDTO = $this->arrayTransformer->fromArray($roomAsArr, Room::class);

                $this->processRoom($roomDTO);

                $this->entityManager->flush();
            }

            $this->meterRepository->setInactiveStatusByBuildingAndUpdatedAt(
                $this->building,
                $cExchange->getCreatedAt()
            );

            $this->roomAccountRepository->setInactiveStatusByBuildingAndUpdatedAt(
                $this->building,
                $cExchange->getCreatedAt()
            );

            // @todo Загрузка показаний

            $this->messageBus->dispatch(
                new UnloadDataTo1CMessage($cExchange)
            );
        } catch (Throwable $exception) {
            captureException($exception);

            if (null !== $cExchange) {
                $cExchange->setStatus(CExchangeStatusEnum::error());

                $this->entityManager->flush();

                /** @var string $cExchangeDir */
                $filesystem = new Filesystem();
                $filesystem->remove($cExchangeDir);
            }
        }
    }

    /**
     * @throws JsonException
     */
    private function getNextRoom(string $fileContents): Generator
    {
        yield from json_decode($fileContents, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param Account\Meter[] $meters
     *
     * @throws DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws Exception
     * @throws \Exception
     */
    private function processMeters(RoomAccount $roomAccount, array $meters): void
    {
        $communalServices =
            array_map(
                static fn (BuildingCommunalService $buildingCommunalService): CommunalService => $buildingCommunalService->getCommunalService(),
                $this->buildingCommunalServices
            );

        foreach ($meters as $meterDTO) {
            $communalServiceTitle = $meterDTO->communalService->getTitle();

            $communalService = null;

            foreach ($communalServices as $value) {
                if ($communalServiceTitle === $value->getTitle()) {
                    $communalService = $value;

                    break;
                }
            }

            if (null === $communalService) {
                $communalService =
                    new CommunalService(
                        Uuid::v4(),
                        $communalServiceTitle
                    );

                $this->entityManager->persist($communalService);
            }

            $buildingCommunalService = null;

            foreach ($this->buildingCommunalServices as $value) {
                if ($meterDTO->communalService->id === $value->getExternalId()->toRfc4122()) {
                    $buildingCommunalService = $value;

                    break;
                }
            }

            if (null !== $buildingCommunalService) {
                $buildingCommunalService->setCommunalService($communalService);
            } else {
                $buildingCommunalService =
                    new BuildingCommunalService(
                        $this->building,
                        $communalService,
                        Uuid::fromString($meterDTO->communalService->id),
                        Uuid::v4()
                    );

                $this->entityManager->persist($buildingCommunalService);

                $this->buildingCommunalServices[] = $buildingCommunalService;
            }

            $lastIndicationPeriodTimeZone = new DateTimeZone($this->building->getTimeOffset());

            $meterStatus =
                true === $meterDTO->isActive
                    ? MeterStatusEnum::active()
                    : MeterStatusEnum::inactive();

            $meterTitle = $meterDTO->getTitle();

            $meter = null;

            foreach ($roomAccount->getMeters() as $value) {
                if ($meterDTO->id === $value->getExternalId()->toRfc4122()) {
                    $meter = $value;

                    break;
                }
            }

            if (null !== $meter) {
                $meter->setBuildingCommunalService($buildingCommunalService);
                $meter->setIndicationsCount($meterDTO->indicationsCount);
                $meter->setStatus($meterStatus);
                $meter->setTitle($meterTitle);

                if (null !== $meterDTO->lastIndication) {
                    /** @var DateTimeImmutable $period */
                    $period =
                        DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $meterDTO->lastIndication->period, $lastIndicationPeriodTimeZone);

                    $this->meterIndicationRepository->persistLastIndication($meterDTO->lastIndication, $meter, $period);
                }
            } else {
                $meter =
                    new Meter(
                        $buildingCommunalService,
                        Uuid::fromString($meterDTO->id),
                        Uuid::v4(),
                        $meterDTO->indicationsCount,
                        $roomAccount,
                        $meterStatus,
                        $meterTitle
                    );

                $this->entityManager->persist($meter);

                if (null !== $meterDTO->lastIndication) {
                    /** @var DateTimeImmutable $period */
                    $period =
                        DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $meterDTO->lastIndication->period, $lastIndicationPeriodTimeZone);

                    $meterIndication =
                        new MeterIndication(
                            $meterDTO->lastIndication->getDayIndication(),
                            Uuid::v4(),
                            $meter,
                            $meterDTO->lastIndication->getNightIndication(),
                            $meterDTO->lastIndication->getPeakIndication(),
                            $period
                        );

                    $this->entityManager->persist($meterIndication);
                }
            }
        }
    }

    /**
     * @throws DBALException
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws NonUniqueResultException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    private function processRoom(Room $roomDTO): void
    {
        try {
            $room =
                $this->roomRepository->findOneByBuildingAndExternalIdWithAccountsMetersAndCommunalServices(
                    $this->building,
                    $roomDTO->id
                );

            $room->setCode($roomDTO->code);
            $room->setSuffix($roomDTO->suffix);
        } catch (NoResultException) {
            $room =
                new \App\Entity\Room(
                    $this->building,
                    $roomDTO->code,
                    Uuid::fromString($roomDTO->id),
                    Uuid::v4(),
                    $roomDTO->suffix
                );

            $this->entityManager->persist($room);
        }

        $this->processRoomAccounts($room, $roomDTO->accounts);
    }

    /**
     * @param Account[] $roomAccounts
     *
     * @throws DBALException
     * @throws Exception
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    private function processRoomAccounts(\App\Entity\Room $room, array $roomAccounts): void
    {
        foreach ($roomAccounts as $accountDTO) {
            $status =
                true === $accountDTO->isActive
                    ? RoomAccountStatusEnum::active()
                    : RoomAccountStatusEnum::inactive();

            $roomAccount = null;

            foreach ($room->getRoomAccounts() as $value) {
                if ($accountDTO->id === $value->getExternalId()->toRfc4122()) {
                    $roomAccount = $value;

                    break;
                }
            }

            if (null !== $roomAccount) {
                $roomAccount->setStatus($status);
            } else {
                $roomAccount =
                    new RoomAccount(
                        Uuid::fromString($accountDTO->id),
                        Uuid::v4(),
                        $room,
                        $status
                    );

                $this->entityManager->persist($roomAccount);
            }

            $this->processMeters($roomAccount, $accountDTO->meters);
        }
    }
}
