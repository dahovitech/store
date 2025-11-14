<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductOptionGroup;
use App\Entity\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductOptionGroup>
 */
class ProductOptionGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductOptionGroup::class);
    }

    /**
     * @return ProductOptionGroup[] Returns active groups ordered by sort order
     */
    public function findActiveOrdered(): array
    {
        return $this->createQueryBuilder('pog')
            ->andWhere('pog.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('pog.sortOrder', 'ASC')
            ->addOrderBy('pog.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find groups by type
     */
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('pog')
            ->andWhere('pog.type = :type')
            ->andWhere('pog.isActive = :active')
            ->setParameter('type', $type)
            ->setParameter('active', true)
            ->orderBy('pog.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find groups available for a specific category
     */
    public function findByCategory(ProductCategory $category): array
    {
        return $this->createQueryBuilder('pog')
            ->leftJoin('pog.categories', 'pc')
            ->andWhere('pc = :category')
            ->andWhere('pog.isActive = :active')
            ->setParameter('category', $category)
            ->setParameter('active', true)
            ->orderBy('pog.sortOrder', 'ASC')
            ->addOrderBy('pog.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find required groups
     */
    public function findRequired(): array
    {
        return $this->createQueryBuilder('pog')
            ->andWhere('pog.isRequired = :required')
            ->andWhere('pog.isActive = :active')
            ->setParameter('required', true)
            ->setParameter('active', true)
            ->orderBy('pog.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find group by slug
     */
    public function findOneBySlug(string $slug): ?ProductOptionGroup
    {
        return $this->createQueryBuilder('pog')
            ->andWhere('pog.slug = :slug')
            ->andWhere('pog.isActive = :active')
            ->setParameter('slug', $slug)
            ->setParameter('active', true)
            ->getQuery()
            ->getOneOrNullResult();
    }
}