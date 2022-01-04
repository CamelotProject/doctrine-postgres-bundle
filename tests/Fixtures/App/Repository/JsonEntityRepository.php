<?php

declare(strict_types=1);

namespace Camelot\DoctrinePostgres\Tests\Fixtures\App\Repository;

use Camelot\DoctrinePostgres\Tests\Fixtures\App\Entity\JsonEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JsonEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method JsonEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method JsonEntity[]    findAll()
 * @method JsonEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JsonEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JsonEntity::class);
    }

    public function findOneLike(string $value): JsonEntity
    {
        return $this->createQueryBuilder('j')
            ->andWhere('ILIKE(j.title, :val) = TRUE')
            ->setParameter('val', $value)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    public function findAllLike(string $value): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('ILIKE(j.title, :val) = TRUE')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }
}
