<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductOptionRepository::class)]
#[ORM\Table(name: 'product_options')]
#[ORM\HasLifecycleCallbacks]
class ProductOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    #[Gedmo\Slug(fields: ['name'], unique: true)]
    private string $slug;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    #[Gedmo\Translatable]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Gedmo\Translatable]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $value = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $pricePercentage = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isDefault = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'integer')]
    private int $sortOrder = 0;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $color = null;

    #[ORM\ManyToOne(targetEntity: ProductOptionGroup::class, inversedBy: 'options')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductOptionGroup $group = null;

    #[ORM\OneToMany(mappedBy: 'option', targetEntity: ProductOptionValue::class, cascade: ['persist', 'remove'])]
    private Collection $optionValues;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->optionValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;
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

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): static
    {
        $this->isDefault = $isDefault;
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

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;
        return $this;
    }

    public function getGroup(): ?ProductOptionGroup
    {
        return $this->group;
    }

    public function setGroup(?ProductOptionGroup $group): static
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return Collection<int, ProductOptionValue>
     */
    public function getOptionValues(): Collection
    {
        return $this->optionValues;
    }

    public function addOptionValue(ProductOptionValue $optionValue): static
    {
        if (!$this->optionValues->contains($optionValue)) {
            $this->optionValues->add($optionValue);
            $optionValue->setOption($this);
        }
        return $this;
    }

    public function removeOptionValue(ProductOptionValue $optionValue): static
    {
        if ($this->optionValues->removeElement($optionValue)) {
            if ($optionValue->getOption() === $this) {
                $optionValue->setOption(null);
            }
        }
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
        return $this->name;
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

        return null;
    }
}