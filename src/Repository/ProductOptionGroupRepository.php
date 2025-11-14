<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductOptionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductOptionGroup>
 */
class ProductOptionGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductOptionGroup::class);
    }

    public function save(ProductOptionGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductOptionGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ProductOptionGroup[] Returns an array of ProductOptionGroup objects
     */
    public function findActiveGroups(): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('g.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find group by slug
     */
    public function findOneBySlug(string $slug): ?ProductOptionGroup
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.slug = :slug')
            ->andWhere('g.isActive = :isActive')
            ->setParameter('slug', $slug)
            ->setParameter('isActive', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find groups by type
     */
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.type = :type')
            ->andWhere('g.isActive = :isActive')
            ->setParameter('type', $type)
            ->setParameter('isActive', true)
            ->orderBy('g.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find required groups
     */
    public function findRequiredGroups(): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.isRequired = :isRequired')
            ->andWhere('g.isActive = :isActive')
            ->setParameter('isRequired', true)
            ->setParameter('isActive', true)
            ->orderBy('g.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find groups with their options
     */
    public function findGroupsWithOptions(): array
    {
        return $this->createQueryBuilder('g')
            ->select('g', 'o')
            ->leftJoin('g.options', 'o')
            ->andWhere('g.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('g.sortOrder', 'ASC')
            ->addOrderBy('o.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Search groups by name or description
     */
    public function searchGroups(string $query): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.isActive = :isActive')
            ->andWhere('g.name LIKE :query OR g.description LIKE :query')
            ->setParameter('isActive', true)
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('g.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Count options per group
     */
    public function countOptionsByGroup(): array
    {
        return $this->createQueryBuilder('g')
            ->select('g.id, g.name, COUNT(o.id) as option_count')
            ->leftJoin('g.options', 'o')
            ->andWhere('g.isActive = :isActive')
            ->setParameter('isActive', true)
            ->groupBy('g.id')
            ->orderBy('option_count', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all option types
     */
    public function findOptionTypes(): array
    {
        return $this->createQueryBuilder('g')
            ->select('DISTINCT g.type')
            ->andWhere('g.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('g.type', 'ASC')
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * Find groups with their option counts
     */
    public function findGroupsWithOptionCount(): array
    {
        return $this->createQueryBuilder('g')
            ->select('g')
            ->addSelect('COUNT(o.id) as option_count')
            ->leftJoin('g.options', 'o')
            ->andWhere('g.isActive = :isActive')
            ->setParameter('isActive', true)
            ->groupBy('g.id')
            ->orderBy('g.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find groups with max selections constraint
     */
    public function findGroupsWithMaxSelections(int $maxSelections = null): array
    {
        $qb = $this->createQueryBuilder('g')
            ->andWhere('g.isActive = :isActive')
            ->setParameter('isActive', true);

        if ($maxSelections !== null) {
            $qb->andWhere('g.maxSelections <= :maxSelections')
               ->setParameter('maxSelections', $maxSelections);
        }

        return $qb->orderBy('g.sortOrder', 'ASC')
                  ->getQuery()
                  ->getResult();
    }
}