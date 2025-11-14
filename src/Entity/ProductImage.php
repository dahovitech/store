<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductImageRepository::class)]
#[ORM\Table(name: 'product_images')]
#[ORM\HasLifecycleCallbacks]
class ProductImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Media::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Media $media = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Product $product = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    #[Assert\Length(max: 100)]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Assert\Length(max: 50)]
    private ?string $alt = null;

    #[ORM\Column(type: 'integer')]
    private int $sortOrder = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $isMain = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    private ?string $imageType = null; // 'exterior', 'interior', 'detail', 'technical', 'lifestyle'

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(Media $media): static
    {
        $this->media = $media;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): static
    {
        $this->alt = $alt;
        return $this;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    public function isMain(): bool
    {
        return $this->isMain;
    }

    public function setIsMain(bool $isMain): static
    {
        $this->isMain = $isMain;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getImageType(): ?string
    {
        return $this->imageType;
    }

    public function setImageType(?string $imageType): static
    {
        $this->imageType = $imageType;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function __toString(): string
    {
        return $this->title ?? $this->media?->getAlt() ?? 'Image #' . ($this->id ?? 'new');
    }

    /**
     * Get the public URL of the media file
     */
    public function getUrl(): string
    {
        return $this->media?->getUrl() ?? '';
    }

    /**
     * Get file name
     */
    public function getFileName(): ?string
    {
        return $this->media?->getFileName();
    }

    /**
     * Get alt text with fallback
     */
    public function getEffectiveAlt(): string
    {
        return $this->alt ?? $this->title ?? $this->media?->getAlt() ?? '';
    }

    /**
     * Check if this image type is an exterior image
     */
    public function isExterior(): bool
    {
        return $this->imageType === 'exterior';
    }

    /**
     * Check if this image type is an interior image
     */
    public function isInterior(): bool
    {
        return $this->imageType === 'interior';
    }

    /**
     * Check if this image type is a detail image
     */
    public function isDetail(): bool
    {
        return $this->imageType === 'detail';
    }

    /**
     * Check if this image type is a technical image
     */
    public function isTechnical(): bool
    {
        return $this->imageType === 'technical';
    }

    /**
     * Check if this image type is a lifestyle image
     */
    public function isLifestyle(): bool
    {
        return $this->imageType === 'lifestyle';
    }
}