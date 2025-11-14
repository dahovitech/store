<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductOptionValueRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductOptionValueRepository::class)]
#[ORM\Table(name: 'product_option_values')]
#[ORM\UniqueConstraint(name: 'unique_product_option', columns: ['product_id', 'option_id'])]
#[ORM\HasLifecycleCallbacks]
class ProductOptionValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'optionValues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(targetEntity: ProductOption::class, inversedBy: 'optionValues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductOption $option = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $customValue = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $pricePercentage = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isSelected = false;

    #[ORM\Column(type: 'integer')]
    private int $sortOrder = 0;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;
        return $this;
    }

    public function getOption(): ?ProductOption
    {
        return $this->option;
    }

    public function setOption(?ProductOption $option): static
    {
        $this->option = $option;
        return $this;
    }

    public function getCustomValue(): ?string
    {
        return $this->customValue;
    }

    public function setCustomValue(?string $customValue): static
    {
        $this->customValue = $customValue;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getPricePercentage(): ?string
    {
        return $this->pricePercentage;
    }

    public function setPricePercentage(?string $pricePercentage): static
    {
        $this->pricePercentage = $pricePercentage;
        return $this;
    }

    public function isSelected(): bool
    {
        return $this->isSelected;
    }

    public function setIsSelected(bool $isSelected): static
    {
        $this->isSelected = $isSelected;
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
        return $this->option?->getName() ?? 'Option Value #' . ($this->id ?? 'new');
    }

    /**
     * Get the effective value (custom value if set, otherwise option's value)
     */
    public function getEffectiveValue(): ?string
    {
        return $this->customValue ?? $this->option?->getValue();
    }

    /**
     * Get the final price including percentage adjustment
     */
    public function getFinalPrice(?string $basePrice = null): ?string
    {
        if ($this->price !== null) {
            return $this->price;
        }

        if ($this->pricePercentage !== null && $basePrice !== null) {
            $base = floatval($basePrice);
            $percentage = floatval($this->pricePercentage);
            return (string) ($base * (1 + $percentage / 100));
        }

        return $this->option?->getFinalPrice($basePrice);
    }
}