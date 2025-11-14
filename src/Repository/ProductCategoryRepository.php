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

    public function save(ProductCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ProductCategory[] Returns an array of ProductCategory objects
     */
    public function findActiveCategories(int $limit = null): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('c.sortOrder', 'ASC')
            ->addOrderBy('c.position', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ProductCategory[] Returns an array of ProductCategory objects ordered by position
     */
    public function findByPosition(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.position', 'ASC')
            ->addOrderBy('c.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find category by slug
     */
    public function findOneBySlug(string $slug): ?ProductCategory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.slug = :slug')
            ->andWhere('c.isActive = :isActive')
            ->setParameter('slug', $slug)
            ->setParameter('isActive', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find category by ID
     */
    public function findOneById(int $id): ?ProductCategory
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Count products per category
     */
    public function countProductsByCategory(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.id, c.name, COUNT(p.id) as product_count')
            ->leftJoin('c.products', 'p')
            ->andWhere('c.isActive = :isActive')
            ->setParameter('isActive', true)
            ->groupBy('c.id')
            ->orderBy('product_count', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find categories with their product counts
     */
    public function findCategoriesWithProductCount(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->addSelect('COUNT(p.id) as product_count')
            ->leftJoin('c.products', 'p')
            ->andWhere('c.isActive = :isActive')
            ->setParameter('isActive', true)
            ->groupBy('c.id')
            ->orderBy('c.position', 'ASC')
            ->addOrderBy('c.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}