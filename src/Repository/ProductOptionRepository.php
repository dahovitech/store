<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductOption>
 */
class ProductOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductOption::class);
    }

    public function save(ProductOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByProductAndActive(int $productId): array
    {
        return $this->createQueryBuilder('po')
            ->andWhere('po.product = :productId')
            ->andWhere('po.isActive = :active')
            ->setParameter('productId', $productId)
            ->setParameter('active', true)
            ->orderBy('po.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('po')
            ->andWhere('po.type = :type')
            ->andWhere('po.isActive = :active')
            ->setParameter('type', $type)
            ->setParameter('active', true)
            ->orderBy('po.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
