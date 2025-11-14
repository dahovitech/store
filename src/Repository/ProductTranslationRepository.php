<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductTranslation;
use App\Entity\Language;
use App\Entity\Product;
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

    /**
     * Find translation by product and language
     */
    public function findByProductAndLanguage(Product $product, Language $language): ?ProductTranslation
    {
        return $this->createQueryBuilder('pt')
            ->andWhere('pt.product = :product')
            ->andWhere('pt.language = :language')
            ->setParameter('product', $product)
            ->setParameter('language', $language)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find all translations for a product
     */
    public function findByProduct(Product $product): array
    {
        return $this->createQueryBuilder('pt')
            ->leftJoin('pt.language', 'l')
            ->andWhere('pt.product = :product')
            ->setParameter('product', $product)
            ->orderBy('l.sortOrder', 'ASC')
            ->addOrderBy('l.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find translations by language
     */
    public function findByLanguage(Language $language): array
    {
        return $this->createQueryBuilder('pt')
            ->leftJoin('pt.product', 'p')
            ->andWhere('pt.language = :language')
            ->andWhere('p.isActive = :active')
            ->setParameter('language', $language)
            ->setParameter('active', true)
            ->orderBy('pt.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Search translations by name or description
     */
    public function searchTranslations(string $query, ?Language $language = null): array
    {
        $qb = $this->createQueryBuilder('pt')
            ->leftJoin('pt.product', 'p')
            ->leftJoin('pt.language', 'l')
            ->andWhere('pt.name LIKE :query OR pt.description LIKE :query OR pt.shortDescription LIKE :query')
            ->andWhere('p.isActive = :active')
            ->setParameter('query', '%' . $query . '%')
            ->setParameter('active', true);

        if ($language) {
            $qb->andWhere('pt.language = :language')
               ->setParameter('language', $language);
        }

        return $qb->orderBy('pt.name', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Find missing translations for a product
     */
    public function findMissingTranslations(Product $product, array $availableLanguages): array
    {
        $existingTranslations = $this->createQueryBuilder('pt')
            ->select('l.code')
            ->leftJoin('pt.language', 'l')
            ->andWhere('pt.product = :product')
            ->setParameter('product', $product)
            ->getQuery()
            ->getScalarResult();

        $existingCodes = array_column($existingTranslations, 'code');
        
        return array_filter($availableLanguages, function($language) use ($existingCodes) {
            return !in_array($language->getCode(), $existingCodes);
        });
    }
}