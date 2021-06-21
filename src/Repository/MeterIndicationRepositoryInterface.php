<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\CExchange\Part\Room\Account\Meter\LastIndication;
use App\Entity\Meter;
use DateTimeImmutable;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception;

interface MeterIndicationRepositoryInterface
{
    /**
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws DBALException
     * @throws Exception
     */
    public function persistLastIndication(LastIndication $lastIndication, Meter $meter, DateTimeImmutable $period): void;
}
