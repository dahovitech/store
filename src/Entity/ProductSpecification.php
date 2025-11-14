<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductSpecificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductSpecificationRepository::class)]
#[ORM\Table(name: 'product_specifications')]
#[ORM\UniqueConstraint(name: 'unique_product_specification', columns: ['product_id', 'category', 'specification_key'])]
class ProductSpecification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    private string $category; // 'dimensions', 'materiaux', 'performance', 'equipements', etc.

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    private string $specificationKey;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    private string $value;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $unit = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\PositiveOrZero]
    private int $sortOrder = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $isHighlighted = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'specifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getSpecificationKey(): string
    {
        return $this->specificationKey;
    }

    public function setSpecificationKey(string $specificationKey): static
    {
        $this->specificationKey = $specificationKey;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;
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

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    public function isHighlighted(): bool
    {
        return $this->isHighlighted;
    }

    public function setIsHighlighted(bool $isHighlighted): static
    {
        $this->isHighlighted = $isHighlighted;
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;
        return $this;
    }

    public function getFormattedValue(): string
    {
        return $this->unit ? $this->value . ' ' . $this->unit : $this->value;
    }

    public function __toString(): string
    {
        return $this->specificationKey . ': ' . $this->getFormattedValue();
    }
}
