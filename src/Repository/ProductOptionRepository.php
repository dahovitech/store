<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductOption;
use App\Entity\ProductOptionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductOption>
 */
class ProductOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductOption::class);
    }

    public function save(ProductOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductOption $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ProductOption[] Returns an array of ProductOption objects
     */
    public function findActiveOptions(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('o.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ProductOption[] Returns an array of options by group
     */
    public function findByGroup(ProductOptionGroup $group): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.group = :group')
            ->andWhere('o.isActive = :isActive')
            ->setParameter('group', $group)
            ->setParameter('isActive', true)
            ->orderBy('o.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find option by slug
     */
    public function findOneBySlug(string $slug): ?ProductOption
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.slug = :slug')
            ->andWhere('o.isActive = :isActive')
            ->setParameter('slug', $slug)
            ->setParameter('isActive', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find default options
     */
    public function findDefaultOptions(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.isDefault = :isDefault')
            ->andWhere('o.isActive = :isActive')
            ->setParameter('isDefault', true)
            ->setParameter('isActive', true)
            ->orderBy('o.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find options by type
     */
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('o')
            ->join('o.group', 'g')
            ->andWhere('g.type = :type')
            ->andWhere('o.isActive = :isActive')
            ->setParameter('type', $type)
            ->setParameter('isActive', true)
            ->orderBy('o.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find options with price
     */
    public function findOptionsWithPrice(): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.isActive = :isActive')
            ->andWhere('o.price IS NOT NULL OR o.pricePercentage IS NOT NULL')
            ->setParameter('isActive', true)
            ->orderBy('o.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find options by color
     */
    public function findByColor(string $color): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.color = :color')
            ->andWhere('o.isActive = :isActive')
            ->setParameter('color', $color)
            ->setParameter('isActive', true)
            ->orderBy('o.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get options grouped by their groups
     */
    public function findGroupedOptions(): array
    {
        $options = $this->createQueryBuilder('o')
            ->select('o', 'g')
            ->join('o.group', 'g')
            ->andWhere('o.isActive = :isActive')
            ->andWhere('g.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('g.sortOrder', 'ASC')
            ->addOrderBy('o.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();

        $grouped = [];
        foreach ($options as $option) {
            $groupId = $option->getGroup()->getId();
            if (!isset($grouped[$groupId])) {
                $grouped[$groupId] = [
                    'group' => $option->getGroup(),
                    'options' => []
                ];
            }
            $grouped[$groupId]['options'][] = $option;
        }

        return array_values($grouped);
    }

    /**
     * Find required options
     */
    public function findRequiredOptions(): array
    {
        return $this->createQueryBuilder('o')
            ->join('o.group', 'g')
            ->andWhere('g.isRequired = :isRequired')
            ->andWhere('o.isActive = :isActive')
            ->andWhere('g.isActive = :isActive')
            ->setParameter('isRequired', true)
            ->setParameter('isActive', true)
            ->orderBy('g.sortOrder', 'ASC')
            ->addOrderBy('o.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Search options by name or description
     */
    public function searchOptions(string $query): array
    {
        return $this->createQueryBuilder('o')
            ->join('o.group', 'g')
            ->andWhere('o.isActive = :isActive')
            ->andWhere('g.isActive = :isActive')
            ->andWhere('o.name LIKE :query OR o.description LIKE :query OR g.name LIKE :query')
            ->setParameter('isActive', true)
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('g.sortOrder', 'ASC')
            ->addOrderBy('o.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}