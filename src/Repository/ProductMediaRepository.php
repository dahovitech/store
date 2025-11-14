<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductMedia;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductMedia>
 */
class ProductMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductMedia::class);
    }

    public function save(ProductMedia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductMedia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByProduct(Product $product): array
    {
        return $this->createQueryBuilder('pm')
            ->andWhere('pm.product = :product')
            ->setParameter('product', $product)
            ->orderBy('pm.sortOrder', 'ASC')
            ->addOrderBy('pm.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findMainImages(): array
    {
        return $this->createQueryBuilder('pm')
            ->andWhere('pm.isMain = :isMain')
            ->setParameter('isMain', true)
            ->getQuery()
            ->getResult();
    }

    public function findByType(Product $product, string $type): array
    {
        return $this->createQueryBuilder('pm')
            ->andWhere('pm.product = :product')
            ->andWhere('pm.type = :type')
            ->setParameter('product', $product)
            ->setParameter('type', $type)
            ->orderBy('pm.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
