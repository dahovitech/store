<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[] Returns an array of active products ordered by category and sort order
     */
    public function findActiveOrdered(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->andWhere('p.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('c.sortOrder', 'ASC')
            ->addOrderBy('p.sortOrder', 'ASC')
            ->addOrderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns featured products
     */
    public function findFeatured(int $limit = 6): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :active')
            ->andWhere('p.isFeatured = :featured')
            ->setParameter('active', true)
            ->setParameter('featured', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find products by category
     */
    public function findByCategory(ProductCategory $category): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category = :category')
            ->andWhere('p.isActive = :active')
            ->setParameter('category', $category)
            ->setParameter('active', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->addOrderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find product by slug
     */
    public function findOneBySlug(string $slug): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.slug = :slug')
            ->andWhere('p.isActive = :active')
            ->setParameter('slug', $slug)
            ->setParameter('active', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find products by price range
     */
    public function findByPriceRange(?float $minPrice = null, ?float $maxPrice = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :active')
            ->setParameter('active', true);

        if ($minPrice !== null) {
            $qb->andWhere('p.price >= :minPrice')
               ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('p.price <= :maxPrice')
               ->setParameter('maxPrice', $maxPrice);
        }

        return $qb->orderBy('p.price', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find products by surface range
     */
    public function findBySurfaceRange(?int $minSurface = null, ?int $maxSurface = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :active')
            ->setParameter('active', true);

        if ($minSurface !== null) {
            $qb->andWhere('p.surfaceHabitable >= :minSurface')
               ->setParameter('minSurface', $minSurface);
        }

        if ($maxSurface !== null) {
            $qb->andWhere('p.surfaceHabitable <= :maxSurface')
               ->setParameter('maxSurface', $maxSurface);
        }

        return $qb->orderBy('p.surfaceHabitable', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Search products by various criteria
     */
    public function searchProducts(?string $query = null, ?ProductCategory $category = null, ?float $minPrice = null, ?float $maxPrice = null, ?int $minSurface = null, ?int $maxSurface = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.translations', 'pt')
            ->andWhere('p.isActive = :active')
            ->setParameter('active', true);

        if ($query) {
            $qb->andWhere('pt.name LIKE :query OR pt.description LIKE :query OR p.sku LIKE :query')
               ->setParameter('query', '%' . $query . '%');
        }

        if ($category) {
            $qb->andWhere('p.category = :category')
               ->setParameter('category', $category);
        }

        if ($minPrice !== null) {
            $qb->andWhere('p.price >= :minPrice')
               ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('p.price <= :maxPrice')
               ->setParameter('maxPrice', $maxPrice);
        }

        if ($minSurface !== null) {
            $qb->andWhere('p.surfaceHabitable >= :minSurface')
               ->setParameter('minSurface', $minSurface);
        }

        if ($maxSurface !== null) {
            $qb->andWhere('p.surfaceHabitable <= :maxSurface')
               ->setParameter('maxSurface', $maxSurface);
        }

        return $qb->orderBy('p.sortOrder', 'ASC')
                  ->addOrderBy('p.price', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Get products comparison data
     */
    public function getComparisonData(array $productIds): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id IN (:ids)')
            ->andWhere('p.isActive = :active')
            ->setParameter('ids', $productIds)
            ->setParameter('active', true)
            ->orderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult();
    }
}