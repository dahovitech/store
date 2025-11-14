<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductOptionValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductOptionValue>
 */
class ProductOptionValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductOptionValue::class);
    }

    public function save(ProductOptionValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductOptionValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByOptionAndActive(int $optionId): array
    {
        return $this->createQueryBuilder('pov')
            ->andWhere('pov.option = :optionId')
            ->andWhere('pov.isActive = :active')
            ->setParameter('optionId', $optionId)
            ->setParameter('active', true)
            ->orderBy('pov.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findDefaults(int $optionId): array
    {
        return $this->createQueryBuilder('pov')
            ->andWhere('pov.option = :optionId')
            ->andWhere('pov.isDefault = :default')
            ->setParameter('optionId', $optionId)
            ->setParameter('default', true)
            ->getQuery()
            ->getResult();
    }
}
