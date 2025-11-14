<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductOption;
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

    /**
     * @return ProductOptionValue[] Returns an array of ProductOptionValue objects
     */
    public function findByProduct(Product $product): array
    {
        return $this->createQueryBuilder('pov')
            ->andWhere('pov.product = :product')
            ->setParameter('product', $product)
            ->orderBy('pov.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ProductOptionValue[] Returns an array of selected values for a product
     */
    public function findSelectedByProduct(Product $product): array
    {
        return $this->createQueryBuilder('pov')
            ->andWhere('pov.product = :product')
            ->andWhere('pov.isSelected = :isSelected')
            ->setParameter('product', $product)
            ->setParameter('isSelected', true)
            ->orderBy('pov.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ProductOptionValue[] Returns an array of values by option
     */
    public function findByOption(ProductOption $option): array
    {
        return $this->createQueryBuilder('pov')
            ->andWhere('pov.option = :option')
            ->setParameter('option', $option)
            ->orderBy('pov.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find specific product-option combination
     */
    public function findOneByProductAndOption(Product $product, ProductOption $option): ?ProductOptionValue
    {
        return $this->createQueryBuilder('pov')
            ->andWhere('pov.product = :product')
            ->andWhere('pov.option = :option')
            ->setParameter('product', $product)
            ->setParameter('option', $option)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return ProductOptionValue[] Returns an array of values with custom price
     */
    public function findWithCustomPrice(): array
    {
        return $this->createQueryBuilder('pov')
            ->andWhere('pov.price IS NOT NULL OR pov.pricePercentage IS NOT NULL')
            ->orderBy('pov.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find option values for products by group type
     */
    public function findByGroupType(string $groupType): array
    {
        return $this->createQueryBuilder('pov')
            ->join('pov.option', 'o')
            ->join('o.group', 'g')
            ->andWhere('g.type = :groupType')
            ->setParameter('groupType', $groupType)
            ->orderBy('g.sortOrder', 'ASC')
            ->addOrderBy('o.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find selected values with price impact
     */
    public function findSelectedWithPriceImpact(): array
    {
        return $this->createQueryBuilder('pov')
            ->join('pov.product', 'p')
            ->andWhere('pov.isSelected = :isSelected')
            ->andWhere('pov.price IS NOT NULL OR pov.pricePercentage IS NOT NULL')
            ->setParameter('isSelected', true)
            ->orderBy('pov.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all unique values for a specific option across products
     */
    public function findUniqueValuesForOption(ProductOption $option): array
    {
        return $this->createQueryBuilder('pov')
            ->select('DISTINCT pov.customValue')
            ->andWhere('pov.option = :option')
            ->andWhere('pov.customValue IS NOT NULL')
            ->setParameter('option', $option)
            ->orderBy('pov.customValue', 'ASC')
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * Find option values that are selected for multiple products
     */
    public function findCommonSelectedOptions(): array
    {
        return $this->createQueryBuilder('pov')
            ->select('pov.option', 'COUNT(pov.product) as usage_count')
            ->andWhere('pov.isSelected = :isSelected')
            ->setParameter('isSelected', true)
            ->groupBy('pov.option')
            ->having('usage_count > 1')
            ->orderBy('usage_count', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find products with option values by group
     */
    public function findProductsByGroup(string $groupSlug): array
    {
        return $this->createQueryBuilder('pov')
            ->join('pov.option', 'o')
            ->join('o.group', 'g')
            ->andWhere('g.slug = :groupSlug')
            ->setParameter('groupSlug', $groupSlug)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search option values by custom value
     */
    public function searchByCustomValue(string $query): array
    {
        return $this->createQueryBuilder('pov')
            ->join('pov.option', 'o')
            ->join('pov.product', 'p')
            ->andWhere('pov.customValue LIKE :query OR o.name LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get price impact statistics for selected options
     */
    public function getPriceImpactStats(): array
    {
        $qb = $this->createQueryBuilder('pov')
            ->select('COUNT(pov.id) as total_selections')
            ->addSelect('SUM(CASE WHEN pov.price IS NOT NULL THEN 1 ELSE 0 END) as custom_price_count')
            ->addSelect('SUM(CASE WHEN pov.pricePercentage IS NOT NULL THEN 1 ELSE 0 END) as percentage_price_count')
            ->addSelect('AVG(pov.price) as average_custom_price')
            ->addSelect('AVG(pov.pricePercentage) as average_percentage')
            ->andWhere('pov.isSelected = :isSelected')
            ->setParameter('isSelected', true);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Find option values with empty custom values (using default option values)
     */
    public function findUsingDefaultValues(): array
    {
        return $this->createQueryBuilder('pov')
            ->join('pov.option', 'o')
            ->andWhere('pov.customValue IS NULL')
            ->andWhere('pov.isSelected = :isSelected')
            ->setParameter('isSelected', true)
            ->orderBy('pov.option', 'ASC')
            ->getQuery()
            ->getResult();
    }
}