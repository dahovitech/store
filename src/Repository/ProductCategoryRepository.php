<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductCategory>
 */
class ProductCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCategory::class);
    }

    /**
     * @return ProductCategory[] Returns an array of ProductCategory objects
     */
    public function findActiveOrdered(): array
    {
        return $this->createQueryBuilder('pc')
            ->andWhere('pc.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('pc.sortOrder', 'ASC')
            ->addOrderBy('pc.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find category by slug
     */
    public function findOneBySlug(string $slug): ?ProductCategory
    {
        return $this->createQueryBuilder('pc')
            ->andWhere('pc.slug = :slug')
            ->andWhere('pc.isActive = :active')
            ->setParameter('slug', $slug)
            ->setParameter('active', true)
            ->getQuery()
            ->getOneOrNullResult();
    }
}