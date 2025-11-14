<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductOption;
use App\Entity\ProductOptionGroup;
use App\Entity\Product;
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

    /**
     * @return ProductOption[] Returns active options ordered by weight and name
     */
    public function findActiveOrdered(): array
    {
        return $this->createQueryBuilder('po')
            ->leftJoin('po.group', 'pog')
            ->andWhere('po.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('pog.sortOrder', 'ASC')
            ->addOrderBy('po.weight', 'ASC')
            ->addOrderBy('po.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find options by group
     */
    public function findByGroup(ProductOptionGroup $group): array
    {
        return $this->createQueryBuilder('po')
            ->andWhere('po.group = :group')
            ->andWhere('po.isActive = :active')
            ->setParameter('group', $group)
            ->setParameter('active', true)
            ->orderBy('po.weight', 'ASC')
            ->addOrderBy('po.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find default options
     */
    public function findDefaults(): array
    {
        return $this->createQueryBuilder('po')
            ->leftJoin('po.group', 'pog')
            ->andWhere('po.isDefault = :default')
            ->andWhere('po.isActive = :active')
            ->setParameter('default', true)
            ->setParameter('active', true)
            ->orderBy('pog.sortOrder', 'ASC')
            ->addOrderBy('po.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find options available for a specific product
     */
    public function findByProduct(Product $product): array
    {
        return $this->createQueryBuilder('po')
            ->leftJoin('po.products', 'p')
            ->leftJoin('po.group', 'pog')
            ->andWhere('p = :product')
            ->andWhere('po.isActive = :active')
            ->setParameter('product', $product)
            ->setParameter('active', true)
            ->orderBy('pog.sortOrder', 'ASC')
            ->addOrderBy('po.weight', 'ASC')
            ->addOrderBy('po.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find options by price range
     */
    public function findByPriceRange(?float $minPrice = null, ?float $maxPrice = null): array
    {
        $qb = $this->createQueryBuilder('po')
            ->andWhere('po.isActive = :active')
            ->setParameter('active', true);

        if ($minPrice !== null) {
            $qb->andWhere('po.additionalPrice >= :minPrice')
               ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('po.additionalPrice <= :maxPrice')
               ->setParameter('maxPrice', $maxPrice);
        }

        return $qb->orderBy('po.additionalPrice', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find options with color code (for color selection)
     */
    public function findWithColors(): array
    {
        return $this->createQueryBuilder('po')
            ->andWhere('po.colorCode IS NOT NULL')
            ->andWhere('po.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('po.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find option by slug
     */
    public function findOneBySlug(string $slug): ?ProductOption
    {
        return $this->createQueryBuilder('po')
            ->andWhere('po.slug = :slug')
            ->andWhere('po.isActive = :active')
            ->setParameter('slug', $slug)
            ->setParameter('active', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Calculate total additional price for multiple options
     */
    public function calculateTotalAdditionalPrice(array $optionIds): string
    {
        $result = $this->createQueryBuilder('po')
            ->select('SUM(po.additionalPrice) as total')
            ->andWhere('po.id IN (:ids)')
            ->setParameter('ids', $optionIds)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ?? '0.00';
    }
}