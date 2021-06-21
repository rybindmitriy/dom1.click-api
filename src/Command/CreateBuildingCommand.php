<?php

namespace App\Command;

use App\Dto\Request\Building\CreateBuildingRequest;
use App\UseCase\Building\CreateBuildingInterface;
use InvalidArgumentException;
use JMS\Serializer\ArrayTransformerInterface;
use LogicException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

class CreateBuildingCommand extends Command
{
    protected static $defaultName = 'app:buildings:create';

    /**
     * @throws LogicException
     */
    public function __construct(
        private ArrayTransformerInterface $arrayTransformer,
        private CreateBuildingInterface $createBuildingHandler,
    ) {
        parent::__construct();
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function configure(): void
    {
        $this
            ->addOption('address', null, InputOption::VALUE_REQUIRED, 'Адрес здания')
            ->addOption('fiasId', null, InputOption::VALUE_REQUIRED, 'Код ФИАС здания')
            ->addOption('organizationId', null, InputOption::VALUE_REQUIRED, 'ID организации')
            ->addOption('timeOffset', null, InputOption::VALUE_REQUIRED, 'Отклонение от Гринвича');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            /** @var CreateBuildingRequest $createBuildingRequest */
            $createBuildingRequest =
                $this->arrayTransformer->fromArray(
                    [
                        'address'        => $input->getOption('address') ?? '690048, г Владивосток, ул Южно-Уральская, д 10А',
                        'fiasId'         => $input->getOption('fiasId') ?? '74261946-4eb4-4c43-bdc3-8765202d09a4',
                        'organizationId' => $input->getOption('organizationId') ?? 'dc6cc3cd-34ef-4f3f-ba13-272c97f6bd05',
                        'timeOffset'     => $input->getOption('timeOffset') ?? '+1000',
                    ],
                    CreateBuildingRequest::class
                );

            $this->createBuildingHandler->execute($createBuildingRequest);

            $io->success('Здание добавлено.');
        } catch (Throwable $exception) {
            $io->error($exception->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
