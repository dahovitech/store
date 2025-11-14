<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products')]
#[ORM\HasLifecycleCallbacks]
class Product
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
    private ?string $shortDescription = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Gedmo\Translatable]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Gedmo\Translatable]
    private ?string $features = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Gedmo\Translatable]
    private ?string $specifications = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private string $price;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $pricePerSquareMeter = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?string $surface = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Assert\Length(max: 50)]
    private ?string $dimensions = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    private ?string $assemblyTime = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Assert\Length(max: 50)]
    private ?string $energyClass = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    #[Assert\Length(max: 50)]
    private ?string $constructionType = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $rooms = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $bathrooms = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $bedrooms = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?string $terrace = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?string $floorHeight = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    private ?string $warrantyStructure = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    private ?string $warrantyEquipment = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'boolean')]
    private bool $isFeatured = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isPreOrder = false;

    #[ORM\Column(type: 'integer')]
    private int $stockQuantity = 0;

    #[ORM\Column(type: 'integer')]
    private int $sortOrder = 0;

    #[ORM\Column(type: 'integer')]
    private int $views = 0;

    #[ORM\Column(type: 'integer')]
    private int $sales = 0;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: ProductCategory::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductCategory $category = null;

    #[ORM\OneToOne(mappedBy: 'product', targetEntity: ProductImage::class, cascade: ['persist', 'remove'])]
    private ?ProductImage $mainImage = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductImage::class, cascade: ['persist', 'remove'])]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductOptionValue::class, cascade: ['persist', 'remove'])]
    private Collection $optionValues;

    public function __construct()
    {
        $this->images = new ArrayCollection();
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

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;
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

    public function getFeatures(): ?string
    {
        return $this->features;
    }

    public function setFeatures(?string $features): static
    {
        $this->features = $features;
        return $this;
    }

    public function getSpecifications(): ?string
    {
        return $this->specifications;
    }

    public function setSpecifications(?string $specifications): static
    {
        $this->specifications = $specifications;
        return $this;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getPricePerSquareMeter(): ?string
    {
        return $this->pricePerSquareMeter;
    }

    public function setPricePerSquareMeter(?string $pricePerSquareMeter): static
    {
        $this->pricePerSquareMeter = $pricePerSquareMeter;
        return $this;
    }

    public function getSurface(): ?string
    {
        return $this->surface;
    }

    public function setSurface(?string $surface): static
    {
        $this->surface = $surface;
        return $this;
    }

    public function getDimensions(): ?string
    {
        return $this->dimensions;
    }

    public function setDimensions(?string $dimensions): static
    {
        $this->dimensions = $dimensions;
        return $this;
    }

    public function getAssemblyTime(): ?string
    {
        return $this->assemblyTime;
    }

    public function setAssemblyTime(?string $assemblyTime): static
    {
        $this->assemblyTime = $assemblyTime;
        return $this;
    }

    public function getEnergyClass(): ?string
    {
        return $this->energyClass;
    }

    public function setEnergyClass(?string $energyClass): static
    {
        $this->energyClass = $energyClass;
        return $this;
    }

    public function getConstructionType(): ?string
    {
        return $this->constructionType;
    }

    public function setConstructionType(?string $constructionType): static
    {
        $this->constructionType = $constructionType;
        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(?int $rooms): static
    {
        $this->rooms = $rooms;
        return $this;
    }

    public function getBathrooms(): ?int
    {
        return $this->bathrooms;
    }

    public function setBathrooms(?int $bathrooms): static
    {
        $this->bathrooms = $bathrooms;
        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(?int $bedrooms): static
    {
        $this->bedrooms = $bedrooms;
        return $this;
    }

    public function getTerrace(): ?string
    {
        return $this->terrace;
    }

    public function setTerrace(?string $terrace): static
    {
        $this->terrace = $terrace;
        return $this;
    }

    public function getFloorHeight(): ?string
    {
        return $this->floorHeight;
    }

    public function setFloorHeight(?string $floorHeight): static
    {
        $this->floorHeight = $floorHeight;
        return $this;
    }

    public function getWarrantyStructure(): ?string
    {
        return $this->warrantyStructure;
    }

    public function setWarrantyStructure(?string $warrantyStructure): static
    {
        $this->warrantyStructure = $warrantyStructure;
        return $this;
    }

    public function getWarrantyEquipment(): ?string
    {
        return $this->warrantyEquipment;
    }

    public function setWarrantyEquipment(?string $warrantyEquipment): static
    {
        $this->warrantyEquipment = $warrantyEquipment;
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

    public function isFeatured(): bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(bool $isFeatured): static
    {
        $this->isFeatured = $isFeatured;
        return $this;
    }

    public function isPreOrder(): bool
    {
        return $this->isPreOrder;
    }

    public function setIsPreOrder(bool $isPreOrder): static
    {
        $this->isPreOrder = $isPreOrder;
        return $this;
    }

    public function getStockQuantity(): int
    {
        return $this->stockQuantity;
    }

    public function setStockQuantity(int $stockQuantity): static
    {
        $this->stockQuantity = $stockQuantity;
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

    public function getViews(): int
    {
        return $this->views;
    }

    public function setViews(int $views): static
    {
        $this->views = $views;
        return $this;
    }

    public function getSales(): int
    {
        return $this->sales;
    }

    public function setSales(int $sales): static
    {
        $this->sales = $sales;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
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

    public function getCategory(): ?ProductCategory
    {
        return $this->category;
    }

    public function setCategory(?ProductCategory $category): static
    {
        $this->category = $category;
        return $this;
    }

    public function getMainImage(): ?ProductImage
    {
        return $this->mainImage;
    }

    public function setMainImage(?ProductImage $mainImage): static
    {
        if ($mainImage === null && $this->mainImage !== null) {
            $this->mainImage->setProduct(null);
        }

        if ($mainImage !== null && $mainImage->getProduct() !== $this) {
            $mainImage->setProduct($this);
        }

        $this->mainImage = $mainImage;
        return $this;
    }

    /**
     * @return Collection<int, ProductImage>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ProductImage $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProduct($this);
        }
        return $this;
    }

    public function removeImage(ProductImage $image): static
    {
        if ($this->images->removeElement($image)) {
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }
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
            $optionValue->setProduct($this);
        }
        return $this;
    }

    public function removeOptionValue(ProductOptionValue $optionValue): static
    {
        if ($this->optionValues->removeElement($optionValue)) {
            if ($optionValue->getProduct() === $this) {
                $optionValue->setProduct(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Increment view count
     */
    public function incrementViews(): static
    {
        $this->views++;
        return $this;
    }

    /**
     * Get selected options for this product
     */
    public function getSelectedOptions(): array
    {
        return $this->optionValues
            ->filter(fn($value) => $value->isSelected())
            ->getValues();
    }

    /**
     * Calculate final price with selected options
     */
    public function getFinalPrice(): string
    {
        $totalPrice = floatval($this->price);

        foreach ($this->getSelectedOptions() as $optionValue) {
            $optionPrice = $optionValue->getFinalPrice($this->price);
            if ($optionPrice !== null) {
                $totalPrice += floatval($optionPrice);
            }
        }

        return (string) $totalPrice;
    }
}