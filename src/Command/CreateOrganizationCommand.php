<?php

declare(strict_types=1);

namespace App\Command;

use App\Dto\Request\Organization\CreateOrganizationRequest;
use App\UseCase\Organization\CreateOrganizationInterface;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

class CreateOrganizationCommand extends Command
{
    protected static $defaultName = 'app:organizations:create';

    /**
     * @throws LogicException
     */
    public function __construct(private CreateOrganizationInterface $createOrganizationHandler)
    {
        parent::__construct();
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function configure(): void
    {
        $this
            ->addOption('inn', null, InputOption::VALUE_REQUIRED, 'ИНН организации')
            ->addOption('title', null, InputOption::VALUE_REQUIRED, 'Название организации');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $createOrganizationRequest        = new CreateOrganizationRequest();
            $createOrganizationRequest->inn   = $input->getOption('inn')   ?? '2538140947';
            $createOrganizationRequest->title = $input->getOption('title') ?? 'ТСЖ "ЖК "СОЛНЕЧНЫЙ"';

            $createOrganizationResponse = $this->createOrganizationHandler->execute($createOrganizationRequest);

            $io->success("Организация добавлена. Токен обмена: {$createOrganizationResponse->cExchangeToken}");
        } catch (Throwable $exception) {
            $io->error($exception->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
