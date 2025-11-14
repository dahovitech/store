<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductCategoryTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductCategoryTranslation>
 *
 * @method ProductCategoryTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCategoryTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCategoryTranslation[]    findAll()
 * @method ProductCategoryTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCategoryTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCategoryTranslation::class);
    }

    public function save(ProductCategoryTranslation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductCategoryTranslation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ProductCategoryTranslation[] Find translations by locale
     */
    public function findByLocale(string $locale): array
    {
        return $this->createQueryBuilder('pct')
            ->andWhere('pct.locale = :locale')
            ->setParameter('locale', $locale)
            ->orderBy('pct.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ProductCategoryTranslation|null Find translation by category and locale
     */
    public function findByCategoryAndLocale(int $categoryId, string $locale): ?ProductCategoryTranslation
    {
        return $this->createQueryBuilder('pct')
            ->andWhere('pct.category = :categoryId')
            ->andWhere('pct.locale = :locale')
            ->setParameter('categoryId', $categoryId)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return ProductCategoryTranslation[] Find missing translations for a locale
     */
    public function findMissingTranslations(string $locale, array $categoryIds): array
    {
        return $this->createQueryBuilder('pct')
            ->andWhere('pct.locale = :locale')
            ->setParameter('locale', $locale)
            ->andWhere('pct.category IN (:categoryIds)')
            ->setParameter('categoryIds', $categoryIds)
            ->getQuery()
            ->getResult();
    }
}
