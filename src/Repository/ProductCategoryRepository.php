<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductCategory>
 *
 * @method ProductCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCategory[]    findAll()
 * @method ProductCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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
     * @return ProductCategory[] Returns an array of active ProductCategory objects
     */
    public function findActive(): array
    {
        return $this->createQueryBuilder('pc')
            ->andWhere('pc.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('pc.sortOrder', 'ASC')
            ->addOrderBy('pc.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ProductCategory[] Returns root categories (without parent)
     */
    public function findRootCategories(): array
    {
        return $this->createQueryBuilder('pc')
            ->andWhere('pc.parent IS NULL')
            ->andWhere('pc.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('pc.sortOrder', 'ASC')
            ->addOrderBy('pc.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ProductCategory[] Returns categories by price range
     */
    public function findByPriceRange(string $priceRange): array
    {
        return $this->createQueryBuilder('pc')
            ->andWhere('pc.priceRange = :priceRange')
            ->andWhere('pc.isActive = :active')
            ->setParameter('priceRange', $priceRange)
            ->setParameter('active', true)
            ->orderBy('pc.sortOrder', 'ASC')
            ->addOrderBy('pc.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ProductCategory[] Returns categories with their products count
     */
    public function findWithProductCount(): array
    {
        return $this->createQueryBuilder('pc')
            ->leftJoin('pc.products', 'p')
            ->andWhere('pc.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('pc.sortOrder', 'ASC')
            ->addOrderBy('pc.id', 'ASC')
            ->select('pc, COUNT(p.id) as productCount')
            ->groupBy('pc.id')
            ->getQuery()
            ->getResult();
    }
}
