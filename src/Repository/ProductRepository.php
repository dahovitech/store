<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findActiveProducts(int $limit = null): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns an array of featured products
     */
    public function findFeaturedProducts(int $limit = null): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :isActive')
            ->andWhere('p.isFeatured = :isFeatured')
            ->setParameter('isActive', true)
            ->setParameter('isFeatured', true)
            ->orderBy('p.views', 'DESC')
            ->addOrderBy('p.sortOrder', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns an array of products by category
     */
    public function findByCategory(ProductCategory $category, int $limit = null): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category = :category')
            ->andWhere('p.isActive = :isActive')
            ->setParameter('category', $category)
            ->setParameter('isActive', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->setMaxResults($limit)
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
            ->andWhere('p.isActive = :isActive')
            ->setParameter('slug', $slug)
            ->setParameter('isActive', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find product by ID
     */
    public function findOneById(int $id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Search products by name or description
     */
    public function searchProducts(string $query, int $limit = 20): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :isActive')
            ->andWhere('p.name LIKE :query OR p.shortDescription LIKE :query OR p.description LIKE :query')
            ->setParameter('isActive', true)
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('p.views', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find products by price range
     */
    public function findByPriceRange(?string $minPrice = null, ?string $maxPrice = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :isActive')
            ->setParameter('isActive', true);

        if ($minPrice !== null) {
            $qb->andWhere('p.price >= :minPrice')
               ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice !== null) {
            $qb->andWhere('p.price <= :maxPrice')
               ->setParameter('maxPrice', $maxPrice);
        }

        $qb->orderBy('p.price', 'ASC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Find products by surface range
     */
    public function findBySurfaceRange(?string $minSurface = null, ?string $maxSurface = null, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :isActive')
            ->setParameter('isActive', true);

        if ($minSurface !== null) {
            $qb->andWhere('p.surface >= :minSurface')
               ->setParameter('minSurface', $minSurface);
        }

        if ($maxSurface !== null) {
            $qb->andWhere('p.surface <= :maxSurface')
               ->setParameter('maxSurface', $maxSurface);
        }

        $qb->orderBy('p.surface', 'ASC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Find most viewed products
     */
    public function findMostViewed(int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('p.views', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find best selling products
     */
    public function findBestSelling(int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('p.sales', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find products by energy class
     */
    public function findByEnergyClass(string $energyClass, int $limit = null): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :isActive')
            ->andWhere('p.energyClass = :energyClass')
            ->setParameter('isActive', true)
            ->setParameter('energyClass', $energyClass)
            ->orderBy('p.price', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Increment view count
     */
    public function incrementViews(Product $product): void
    {
        $product->incrementViews();
        $this->getEntityManager()->flush();
    }

    /**
     * Increment sales count
     */
    public function incrementSales(Product $product): void
    {
        $product->setSales($product->getSales() + 1);
        $this->getEntityManager()->flush();
    }

    /**
     * Get statistics
     */
    public function getStats(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.id) as total_products')
            ->addSelect('SUM(CASE WHEN p.isActive = true THEN 1 ELSE 0 END) as active_products')
            ->addSelect('SUM(CASE WHEN p.isFeatured = true THEN 1 ELSE 0 END) as featured_products')
            ->addSelect('AVG(p.price) as average_price')
            ->addSelect('MIN(p.price) as min_price')
            ->addSelect('MAX(p.price) as max_price')
            ->addSelect('SUM(p.views) as total_views')
            ->addSelect('SUM(p.sales) as total_sales');

        return $qb->getQuery()->getSingleResult();
    }
}