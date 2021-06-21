<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\CExchange\Part\Room\Account\Meter\LastIndication;
use App\Entity\Meter;
use App\Entity\MeterIndication;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class MeterIndicationRepository extends ServiceEntityRepository implements MeterIndicationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeterIndication::class);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress UndefinedInterfaceMethod
     */
    public function persistLastIndication(
        LastIndication $lastIndication,
        Meter $meter,
        DateTimeImmutable $period,
    ): void {
        $sql =
            <<<'SQL'
                INSERT INTO meters_indications (id, meter_id, day_indication, month_of_the_period, night_indication, peak_indication,
                                period, year_of_the_period, created_at, updated_at)
                VALUES (:uuid, :meterId, :dayIndication, :monthOfThePeriod, :nightIndication, :peakIndication, :period,
                        :yearOfThePeriod, NOW(), NOW())
                ON CONFLICT (meter_id, year_of_the_period, month_of_the_period) DO UPDATE
                    SET day_indication   = EXCLUDED.day_indication,
                        night_indication = EXCLUDED.night_indication,
                        peak_indication  = EXCLUDED.peak_indication,
                        period           = EXCLUDED.period,
                        updated_at       = NOW()
            SQL;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->executeQuery(
            [
                'dayIndication'    => $lastIndication->getDayIndication(),
                'meterId'          => $meter->getId()->toRfc4122(),
                'monthOfThePeriod' => $period->format('n'),
                'nightIndication'  => $lastIndication->getNightIndication(),
                'peakIndication'   => $lastIndication->getPeakIndication(),
                'period'           => $period->format('Y-m-d H:i:s'),
                'uuid'             => Uuid::v4()->toRfc4122(),
                'yearOfThePeriod'  => $period->format('Y'),
            ]
        );
    }
}
