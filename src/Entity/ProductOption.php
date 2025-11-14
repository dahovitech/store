<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductOptionRepository::class)]
#[ORM\Table(name: 'product_options')]
class ProductOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    private string $slug;

    #[ORM\Column(type: 'string', length: 150)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 150)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $additionalPrice = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    private ?string $multiplier = null; // Multiplicateur de prix (ex: 1.1 pour +10%)

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $weight = null; // Ordre d'affichage

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'boolean')]
    private bool $isDefault = false;

    #[ORM\Column(type: 'string', length: 7, nullable: true)]
    private ?string $colorCode = null; // Pour les options couleur (#FF0000)

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageUrl = null; // Image de prévisualisation

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $metadata = null; // Données supplémentaires

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    // Relations
    #[ORM\ManyToOne(targetEntity: ProductOptionGroup::class, inversedBy: 'options')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductOptionGroup $group = null;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'availableOptions')]
    private Collection $products;

    #[ORM\OneToMany(mappedBy: 'option', targetEntity: ProductConfiguration::class, cascade: ['persist', 'remove'])]
    private Collection $configurations;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->configurations = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
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

    public function getAdditionalPrice(): ?string
    {
        return $this->additionalPrice;
    }

    public function setAdditionalPrice(?string $additionalPrice): static
    {
        $this->additionalPrice = $additionalPrice;
        return $this;
    }

    public function getMultiplier(): ?string
    {
        return $this->multiplier;
    }

    public function setMultiplier(?string $multiplier): static
    {
        $this->multiplier = $multiplier;
        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): static
    {
        $this->weight = $weight;
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

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): static
    {
        $this->isDefault = $isDefault;
        return $this;
    }

    public function getColorCode(): ?string
    {
        return $this->colorCode;
    }

    public function setColorCode(?string $colorCode): static
    {
        $this->colorCode = $colorCode;
        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): static
    {
        $this->metadata = $metadata;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
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
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->products->removeElement($product);
        return $this;
    }

    /**
     * @return Collection<int, ProductConfiguration>
     */
    public function getConfigurations(): Collection
    {
        return $this->configurations;
    }

    public function addConfiguration(ProductConfiguration $configuration): static
    {
        if (!$this->configurations->contains($configuration)) {
            $this->configurations->add($configuration);
            $configuration->setOption($this);
        }

        return $this;
    }

    public function removeConfiguration(ProductConfiguration $configuration): static
    {
        if ($this->configurations->removeElement($configuration)) {
            if ($configuration->getOption() === $this) {
                $configuration->setOption(null);
            }
        }

        return $this;
    }

    public function getTotalAdditionalPrice(): string
    {
        $price = '0.00';
        
        if ($this->additionalPrice !== null) {
            $price = $this->additionalPrice;
        } elseif ($this->multiplier !== null) {
            $price = $this->multiplier;
        }
        
        return $price;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}