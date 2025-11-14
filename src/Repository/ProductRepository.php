<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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
     * @return Product[] Returns an array of active Product objects
     */
    public function findActive(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->addOrderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns products by category
     */
    public function findByCategory(ProductCategory $category): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category = :category')
            ->andWhere('p.isActive = :active')
            ->setParameter('category', $category)
            ->setParameter('active', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->addOrderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns products by price range
     */
    public function findByPriceRange(float $minPrice, float $maxPrice): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.price BETWEEN :minPrice AND :maxPrice')
            ->andWhere('p.isActive = :active')
            ->setParameter('minPrice', $minPrice)
            ->setParameter('maxPrice', $maxPrice)
            ->setParameter('active', true)
            ->orderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns customizable products
     */
    public function findCustomizable(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isCustomizable = :customizable')
            ->andWhere('p.isActive = :active')
            ->setParameter('customizable', true)
            ->setParameter('active', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->addOrderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns in-stock products
     */
    public function findInStock(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isInStock = :inStock')
            ->andWhere('p.isActive = :active')
            ->setParameter('inStock', true)
            ->setParameter('active', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->addOrderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns products by surface habitable range
     */
    public function findBySurfaceRange(int $minSurface, int $maxSurface): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.surfaceHabitable BETWEEN :minSurface AND :maxSurface')
            ->andWhere('p.isActive = :active')
            ->setParameter('minSurface', $minSurface)
            ->setParameter('maxSurface', $maxSurface)
            ->setParameter('active', true)
            ->orderBy('p.surfaceHabitable', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns products with main image
     */
    public function findWithMainImage(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.mainImage', 'mi')
            ->andWhere('p.isActive = :active')
            ->andWhere('mi.id IS NOT NULL')
            ->setParameter('active', true)
            ->orderBy('p.sortOrder', 'ASC')
            ->addOrderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns products sorted by price
     */
    public function findByPriceAsc(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('p.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product[] Returns products sorted by price
     */
    public function findByPriceDesc(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('p.price', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Product|null Find product by code
     */
    public function findOneByCode(string $code): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.code = :code')
            ->andWhere('p.isActive = :active')
            ->setParameter('code', $code)
            ->setParameter('active', true)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
