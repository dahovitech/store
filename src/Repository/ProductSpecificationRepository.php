<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductSpecification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductSpecification>
 */
class ProductSpecificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductSpecification::class);
    }

    public function save(ProductSpecification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductSpecification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByProductAndActive(int $productId): array
    {
        return $this->createQueryBuilder('ps')
            ->andWhere('ps.product = :productId')
            ->andWhere('ps.isActive = :active')
            ->setParameter('productId', $productId)
            ->setParameter('active', true)
            ->orderBy('ps.category', 'ASC')
            ->addOrderBy('ps.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByProductAndCategory(int $productId, string $category): array
    {
        return $this->createQueryBuilder('ps')
            ->andWhere('ps.product = :productId')
            ->andWhere('ps.category = :category')
            ->andWhere('ps.isActive = :active')
            ->setParameter('productId', $productId)
            ->setParameter('category', $category)
            ->setParameter('active', true)
            ->orderBy('ps.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findHighlighted(int $productId): array
    {
        return $this->createQueryBuilder('ps')
            ->andWhere('ps.product = :productId')
            ->andWhere('ps.isHighlighted = :highlighted')
            ->setParameter('productId', $productId)
            ->setParameter('highlighted', true)
            ->orderBy('ps.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
