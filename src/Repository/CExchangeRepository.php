<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Building;
use App\Entity\CExchange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class CExchangeRepository extends ServiceEntityRepository implements CExchangeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CExchange::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findLastOneByBuilding(Building $building): CExchange
    {
        /**
         * @noinspection PhpUnnecessaryLocalVariableInspection
         *
         * @var CExchange
         */
        $result =
            $this->createQueryBuilder('cExchange')
                ->leftJoin(
                    CExchange::class,
                    't2',
                    Join::WITH,
                    'cExchange.building = t2.building AND cExchange.createdAt < t2.createdAt'
                )
                ->where('cExchange.building = :buildingId')
                ->andWhere('t2.id IS NULL')
                ->setParameter('buildingId', $building->getId()->toRfc4122())
                ->getQuery()
                ->getSingleResult();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneById(string $id): CExchange
    {
        /**
         * @noinspection PhpUnnecessaryLocalVariableInspection
         *
         * @var CExchange
         */
        $result =
            $this->createQueryBuilder('cExchange')
                ->where('cExchange.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getSingleResult();

        return $result;
    }

    public function findOneByIdWithBuilding(string $id): CExchange
    {
        /**
         * @noinspection PhpUnnecessaryLocalVariableInspection
         *
         * @var CExchange
         */
        $result =
            $this->createQueryBuilder('cExchange')
                ->addSelect('building')
                ->join('cExchange.building', 'building')
                ->where('cExchange.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getSingleResult();

        return $result;
    }
}
