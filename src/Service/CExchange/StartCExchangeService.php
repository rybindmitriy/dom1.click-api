<?php

declare(strict_types=1);

namespace App\Service\CExchange;

use App\Dto\Response\CExchange\StartCExchangeResponse;
use App\Entity\Building;
use App\Entity\CExchange;
use App\Message\UploadReceivedFrom1CDataMessage;
use App\Repository\CExchangeRepositoryInterface;
use App\UseCase\CExchange\StartCExchangeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Uid\Uuid;
use ZipArchive;

final class StartCExchangeService implements StartCExchangeInterface
{
    private const ALLOWED_MIME_TYPE = 'application/zip';

    public function __construct(
        private CExchangeRepositoryInterface $cExchangeRepository,
        private string $cExchangesRootDir,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus,
        private Security $security,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Request $request): StartCExchangeResponse
    {
        /** @var Building $building */
        $building = $this->security->getUser();

        try {
            $lastCExchange = $this->cExchangeRepository->findLastOneByBuilding($building);

            if (false === $lastCExchange->isCompleted()) {
                throw new BadRequestHttpException();
            }
        } catch (NoResultException) {
        }

        $filesystem = new Filesystem();

        $tempFilename = $filesystem->tempnam('/tmp', 'cExchange_');

        try {
            $filesystem->dumpFile($tempFilename, $request->getContent());

            if (self::ALLOWED_MIME_TYPE !== (new MimeTypes())->guessMimeType($tempFilename)) {
                throw new RuntimeException('Недопустимый формат выгрузки из 1С');
            }

            $zip = new ZipArchive();

            $zip->open($tempFilename);

            $entries = [];

            for ($i = 0; $i < $zip->numFiles; ++$i) {
                $filename = $zip->getNameIndex($i);

                if (true === str_contains($filename, '__rooms__')) {
                    $entries[] = $filename;
                }
            }

            if (1 !== count($entries)) {
                throw new BadRequestHttpException('Не найден файл выгрузки');
            }

            $cExchangeId = Uuid::v4();

            $cExchangeDir = "{$this->cExchangesRootDir}/{$cExchangeId->toRfc4122()}";

            $filesystem->mkdir($cExchangeDir, 0700);

            $zip->extractTo($cExchangeDir, $entries);

            $zip->close();

            $cExchange = new CExchange($building, $cExchangeId);

            $this->entityManager->persist($cExchange);

            $this->entityManager->flush();

            $this->messageBus->dispatch(
                new UploadReceivedFrom1CDataMessage($cExchange)
            );

            return new StartCExchangeResponse($cExchange);
        } finally {
            $filesystem->remove($tempFilename);
        }
    }
}
