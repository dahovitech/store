<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductConfiguration;
use App\Entity\Product;
use App\Entity\ProductOption;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductConfiguration>
 */
class ProductConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductConfiguration::class);
    }

    /**
     * @return ProductConfiguration[] Returns active configurations ordered by sort order
     */
    public function findActiveOrdered(): array
    {
        return $this->createQueryBuilder('pc')
            ->leftJoin('pc.product', 'p')
            ->andWhere('pc.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('pc.sortOrder', 'ASC')
            ->addOrderBy('pc.totalPrice', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find configurations by product
     */
    public function findByProduct(Product $product): array
    {
        return $this->createQueryBuilder('pc')
            ->andWhere('pc.product = :product')
            ->andWhere('pc.isActive = :active')
            ->setParameter('product', $product)
            ->setParameter('active', true)
            ->orderBy('pc.sortOrder', 'ASC')
            ->addOrderBy('pc.totalPrice', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find valid configurations (with date validation)
     */
    public function findValid(): array
    {
        $now = new \DateTimeImmutable();
        
        return $this->createQueryBuilder('pc')
            ->leftJoin('pc.product', 'p')
            ->andWhere('pc.isActive = :active')
            ->andWhere('p.isActive = :productActive')
            ->andWhere('(pc.validFrom IS NULL OR pc.validFrom <= :now)')
            ->andWhere('(pc.validUntil IS NULL OR pc.validUntil >= :now)')
            ->setParameter('active', true)
            ->setParameter('productActive', true)
            ->setParameter('now', $now)
            ->orderBy('pc.sortOrder', 'ASC')
            ->addOrderBy('pc.totalPrice', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find custom configurations by user
     */
    public function findCustomByUser(User $user): array
    {
        return $this->createQueryBuilder('pc')
            ->leftJoin('pc.product', 'p')
            ->andWhere('pc.createdBy = :user')
            ->andWhere('pc.isCustom = :custom')
            ->andWhere('pc.isActive = :active')
            ->andWhere('p.isActive = :productActive')
            ->setParameter('user', $user)
            ->setParameter('custom', true)
            ->setParameter('active', true)
            ->setParameter('productActive', true)
            ->orderBy('pc.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find configuration by slug
     */
    public function findOneBySlug(string $slug): ?ProductConfiguration
    {
        $now = new \DateTimeImmutable();
        
        return $this->createQueryBuilder('pc')
            ->leftJoin('pc.product', 'p')
            ->andWhere('pc.slug = :slug')
            ->andWhere('pc.isActive = :active')
            ->andWhere('p.isActive = :productActive')
            ->andWhere('(pc.validFrom IS NULL OR pc.validFrom <= :now)')
            ->andWhere('(pc.validUntil IS NULL OR pc.validUntil >= :now)')
            ->setParameter('slug', $slug)
            ->setParameter('active', true)
            ->setParameter('productActive', true)
            ->setParameter('now', $now)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find configurations by price range
     */
    public function findByPriceRange(?float $minPrice = null, ?float $maxPrice = null): array
    {
        $qb = $this->createQueryBuilder('pc')
            ->leftJoin('pc.product', 'p')
            ->andWhere('pc.isActive = :active')
            ->andWhere('p.isActive = :productActive')
            ->setParameter('active', true)
            ->setParameter('productActive', true);

        if ($minPrice !== null) {
            $qb->andWhere('pc.totalPrice >= :minPrice')
               ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('pc.totalPrice <= :maxPrice')
               ->setParameter('maxPrice', $maxPrice);
        }

        return $qb->orderBy('pc.totalPrice', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find popular configurations (most used)
     */
    public function findPopular(int $limit = 10): array
    {
        // This would require additional tracking logic
        // For now, return recent configurations
        return $this->createQueryBuilder('pc')
            ->leftJoin('pc.product', 'p')
            ->andWhere('pc.isActive = :active')
            ->andWhere('p.isActive = :productActive')
            ->setParameter('active', true)
            ->setParameter('productActive', true)
            ->orderBy('pc.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Search configurations
     */
    public function searchConfigurations(string $query): array
    {
        return $this->createQueryBuilder('pc')
            ->leftJoin('pc.product', 'p')
            ->leftJoin('pc.product.translations', 'pt')
            ->andWhere('pc.name LIKE :query OR pc.description LIKE :query OR pt.name LIKE :query')
            ->andWhere('pc.isActive = :active')
            ->andWhere('p.isActive = :productActive')
            ->setParameter('query', '%' . $query . '%')
            ->setParameter('active', true)
            ->setParameter('productActive', true)
            ->orderBy('pc.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find expired configurations
     */
    public function findExpired(): array
    {
        $now = new \DateTimeImmutable();
        
        return $this->createQueryBuilder('pc')
            ->andWhere('pc.validUntil IS NOT NULL')
            ->andWhere('pc.validUntil < :now')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get configuration statistics
     */
    public function getStatistics(): array
    {
        $qb = $this->createQueryBuilder('pc')
            ->select('COUNT(pc.id) as total')
            ->addSelect('COUNT(CASE WHEN pc.isCustom = true THEN 1 END) as custom')
            ->addSelect('COUNT(CASE WHEN pc.isActive = true THEN 1 END) as active')
            ->addSelect('AVG(pc.totalPrice) as avgPrice')
            ->addSelect('MIN(pc.totalPrice) as minPrice')
            ->addSelect('MAX(pc.totalPrice) as maxPrice');

        return $qb->getQuery()->getSingleResult();
    }
}