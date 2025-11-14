<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductImage>
 */
class ProductImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImage::class);
    }

    public function save(ProductImage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductImage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ProductImage[] Returns an array of ProductImage objects for a product
     */
    public function findByProduct(Product $product): array
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.product = :product')
            ->andWhere('pi.isActive = :isActive')
            ->setParameter('product', $product)
            ->setParameter('isActive', true)
            ->orderBy('pi.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ProductImage[] Returns an array of main images
     */
    public function findMainImages(): array
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.isMain = :isMain')
            ->andWhere('pi.isActive = :isActive')
            ->setParameter('isMain', true)
            ->setParameter('isActive', true)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find main image for a product
     */
    public function findMainImageForProduct(Product $product): ?ProductImage
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.product = :product')
            ->andWhere('pi.isMain = :isMain')
            ->andWhere('pi.isActive = :isActive')
            ->setParameter('product', $product)
            ->setParameter('isMain', true)
            ->setParameter('isActive', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return ProductImage[] Returns an array of images by type
     */
    public function findByImageType(string $imageType): array
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.imageType = :imageType')
            ->andWhere('pi.isActive = :isActive')
            ->setParameter('imageType', $imageType)
            ->setParameter('isActive', true)
            ->orderBy('pi.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ProductImage[] Returns an array of exterior images for a product
     */
    public function findExteriorImages(Product $product): array
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.product = :product')
            ->andWhere('pi.imageType = :imageType')
            ->andWhere('pi.isActive = :isActive')
            ->setParameter('product', $product)
            ->setParameter('imageType', 'exterior')
            ->setParameter('isActive', true)
            ->orderBy('pi.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ProductImage[] Returns an array of interior images for a product
     */
    public function findInteriorImages(Product $product): array
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.product = :product')
            ->andWhere('pi.imageType = :imageType')
            ->andWhere('pi.isActive = :isActive')
            ->setParameter('product', $product)
            ->setParameter('imageType', 'interior')
            ->setParameter('isActive', true)
            ->orderBy('pi.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ProductImage[] Returns an array of detail images for a product
     */
    public function findDetailImages(Product $product): array
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.product = :product')
            ->andWhere('pi.imageType = :imageType')
            ->andWhere('pi.isActive = :isActive')
            ->setParameter('product', $product)
            ->setParameter('imageType', 'detail')
            ->setParameter('isActive', true)
            ->orderBy('pi.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Search images by title or description
     */
    public function searchImages(string $query): array
    {
        return $this->createQueryBuilder('pi')
            ->join('pi.product', 'p')
            ->andWhere('pi.isActive = :isActive')
            ->andWhere('p.isActive = :isActive')
            ->andWhere('pi.title LIKE :query OR pi.description LIKE :query OR pi.alt LIKE :query OR p.name LIKE :query')
            ->setParameter('isActive', true)
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('pi.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find images by media file
     */
    public function findByMedia(int $mediaId): array
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.media = :mediaId')
            ->setParameter('mediaId', $mediaId)
            ->orderBy('pi.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get all unique image types
     */
    public function findImageTypes(): array
    {
        return $this->createQueryBuilder('pi')
            ->select('DISTINCT pi.imageType')
            ->andWhere('pi.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('pi.imageType', 'ASC')
            ->getQuery()
            ->getScalarResult();
    }

    /**
     * Find images grouped by product with counts
     */
    public function findGroupedByProduct(): array
    {
        $images = $this->createQueryBuilder('pi')
            ->select('pi', 'p')
            ->join('pi.product', 'p')
            ->andWhere('pi.isActive = :isActive')
            ->andWhere('p.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('p.name', 'ASC')
            ->addOrderBy('pi.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();

        $grouped = [];
        foreach ($images as $image) {
            $productId = $image->getProduct()->getId();
            if (!isset($grouped[$productId])) {
                $grouped[$productId] = [
                    'product' => $image->getProduct(),
                    'images' => []
                ];
            }
            $grouped[$productId]['images'][] = $image;
        }

        return array_values($grouped);
    }

    /**
     * Find images without alt text
     */
    public function findWithoutAlt(): array
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.alt IS NULL')
            ->andWhere('pi.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('pi.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find images without title
     */
    public function findWithoutTitle(): array
    {
        return $this->createQueryBuilder('pi')
            ->andWhere('pi.title IS NULL')
            ->andWhere('pi.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('pi.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get image statistics
     */
    public function getImageStats(): array
    {
        $qb = $this->createQueryBuilder('pi')
            ->select('COUNT(pi.id) as total_images')
            ->addSelect('SUM(CASE WHEN pi.isMain = true THEN 1 ELSE 0 END) as main_images')
            ->addSelect('SUM(CASE WHEN pi.isActive = true THEN 1 ELSE 0 END) as active_images')
            ->addSelect('SUM(CASE WHEN pi.title IS NOT NULL THEN 1 ELSE 0 END) as with_title')
            ->addSelect('SUM(CASE WHEN pi.alt IS NOT NULL THEN 1 ELSE 0 END) as with_alt');

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Find duplicate images (same media used multiple times for same product)
     */
    public function findDuplicates(): array
    {
        return $this->createQueryBuilder('pi')
            ->select('pi.media, pi.product, COUNT(pi.id) as usage_count')
            ->groupBy('pi.media, pi.product')
            ->having('usage_count > 1')
            ->orderBy('usage_count', 'DESC')
            ->getQuery()
            ->getResult();
    }
}