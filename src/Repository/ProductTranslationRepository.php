<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductTranslation>
 */
class ProductTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductTranslation::class);
    }

    public function save(ProductTranslation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductTranslation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByLocale(string $locale): array
    {
        return $this->createQueryBuilder('pt')
            ->andWhere('pt.locale = :locale')
            ->setParameter('locale', $locale)
            ->orderBy('pt.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByProductAndLocale(int $productId, string $locale): ?ProductTranslation
    {
        return $this->createQueryBuilder('pt')
            ->andWhere('pt.product = :productId')
            ->andWhere('pt.locale = :locale')
            ->setParameter('productId', $productId)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
