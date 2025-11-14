<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products')]
#[Gedmo\TranslationEntity(class: ProductTranslation::class)]
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
    private string $code;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private string $price;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $pricePerSquareMeter = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $surfaceHabitable = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $surfaceTerrasse = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $nombrePieces = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $nombreChambres = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?string $hauteurSousPlafond = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $dimensions = null; // {"longueur": 6, "largeur": 4, "hauteur": 2.8}

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $performanceEnergetique = null; // {"classe": "B", "coefficient": 0.18}

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $autonomieEnergetique = null; // {"percentage": 90, "panneaux": 4, "batteries": 10}

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $tempsMontage = null; // jours

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    private string $deliveryType; // 'convoy_1', 'convoy_2', 'convoy_3'

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    private string $assemblyType; // 'modulaire', 'traditionnel', 'premium'

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $materiaux = null; // materials used in construction

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $equipementsInclus = null; // included equipment

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $garanties = null; // {"structure": 10, "equipements": 5, "esthetique": 3}

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $specificationsTechniques = null; // technical specs

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $avantages = null; // list of advantages

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $espaceOptimise = null; // optimized space features

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $technologiesEco = null; // eco technologies

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $architectureInnovante = null; // innovative architecture features

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'boolean')]
    private bool $isCustomizable = true;

    #[ORM\Column(type: 'boolean')]
    private bool $isInStock = true;

    #[ORM\Column(type: 'integer')]
    #[Assert\PositiveOrZero]
    private int $sortOrder = 0;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'update')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\ManyToOne(targetEntity: ProductCategory::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductCategory $category = null;

    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Media $mainImage = null;

    #[ORM\OneToMany(targetEntity: ProductTranslation::class, mappedBy: 'product', cascade: ['persist', 'remove'], indexBy: 'locale')]
    private Collection $translations;

    #[ORM\OneToMany(targetEntity: ProductMedia::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    private Collection $media;

    #[ORM\OneToMany(targetEntity: ProductOption::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    private Collection $options;

    #[ORM\OneToMany(targetEntity: ProductSpecification::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
    private Collection $specifications;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->options = new ArrayCollection();
        $this->specifications = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
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

    public function getSurfaceHabitable(): ?int
    {
        return $this->surfaceHabitable;
    }

    public function setSurfaceHabitable(?int $surfaceHabitable): static
    {
        $this->surfaceHabitable = $surfaceHabitable;
        return $this;
    }

    public function getSurfaceTerrasse(): ?int
    {
        return $this->surfaceTerrasse;
    }

    public function setSurfaceTerrasse(?int $surfaceTerrasse): static
    {
        $this->surfaceTerrasse = $surfaceTerrasse;
        return $this;
    }

    public function getNombrePieces(): ?int
    {
        return $this->nombrePieces;
    }

    public function setNombrePieces(?int $nombrePieces): static
    {
        $this->nombrePieces = $nombrePieces;
        return $this;
    }

    public function getNombreChambres(): ?int
    {
        return $this->nombreChambres;
    }

    public function setNombreChambres(?int $nombreChambres): static
    {
        $this->nombreChambres = $nombreChambres;
        return $this;
    }

    public function getHauteurSousPlafond(): ?string
    {
        return $this->hauteurSousPlafond;
    }

    public function setHauteurSousPlafond(?string $hauteurSousPlafond): static
    {
        $this->hauteurSousPlafond = $hauteurSousPlafond;
        return $this;
    }

    public function getDimensions(): ?array
    {
        return $this->dimensions;
    }

    public function setDimensions(?array $dimensions): static
    {
        $this->dimensions = $dimensions;
        return $this;
    }

    public function getPerformanceEnergetique(): ?array
    {
        return $this->performanceEnergetique;
    }

    public function setPerformanceEnergetique(?array $performanceEnergetique): static
    {
        $this->performanceEnergetique = $performanceEnergetique;
        return $this;
    }

    public function getAutonomieEnergetique(): ?array
    {
        return $this->autonomieEnergetique;
    }

    public function setAutonomieEnergetique(?array $autonomieEnergetique): static
    {
        $this->autonomieEnergetique = $autonomieEnergetique;
        return $this;
    }

    public function getTempsMontage(): ?int
    {
        return $this->tempsMontage;
    }

    public function setTempsMontage(?int $tempsMontage): static
    {
        $this->tempsMontage = $tempsMontage;
        return $this;
    }

    public function getDeliveryType(): string
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(string $deliveryType): static
    {
        $this->deliveryType = $deliveryType;
        return $this;
    }

    public function getAssemblyType(): string
    {
        return $this->assemblyType;
    }

    public function setAssemblyType(string $assemblyType): static
    {
        $this->assemblyType = $assemblyType;
        return $this;
    }

    public function getMateriaux(): ?array
    {
        return $this->materiaux;
    }

    public function setMateriaux(?array $materiaux): static
    {
        $this->materiaux = $materiaux;
        return $this;
    }

    public function getEquipementsInclus(): ?array
    {
        return $this->equipementsInclus;
    }

    public function setEquipementsInclus(?array $equipementsInclus): static
    {
        $this->equipementsInclus = $equipementsInclus;
        return $this;
    }

    public function getGaranties(): ?array
    {
        return $this->garanties;
    }

    public function setGaranties(?array $garanties): static
    {
        $this->garanties = $garanties;
        return $this;
    }

    public function getSpecificationsTechniques(): ?array
    {
        return $this->specificationsTechniques;
    }

    public function setSpecificationsTechniques(?array $specificationsTechniques): static
    {
        $this->specificationsTechniques = $specificationsTechniques;
        return $this;
    }

    public function getAvantages(): ?array
    {
        return $this->avantages;
    }

    public function setAvantages(?array $avantages): static
    {
        $this->avantages = $avantages;
        return $this;
    }

    public function getEspaceOptimise(): ?array
    {
        return $this->espaceOptimise;
    }

    public function setEspaceOptimise(?array $espaceOptimise): static
    {
        $this->espaceOptimise = $espaceOptimise;
        return $this;
    }

    public function getTechnologiesEco(): ?array
    {
        return $this->technologiesEco;
    }

    public function setTechnologiesEco(?array $technologiesEco): static
    {
        $this->technologiesEco = $technologiesEco;
        return $this;
    }

    public function getArchitectureInnovante(): ?array
    {
        return $this->architectureInnovante;
    }

    public function setArchitectureInnovante(?array $architectureInnovante): static
    {
        $this->architectureInnovante = $architectureInnovante;
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

    public function isCustomizable(): bool
    {
        return $this->isCustomizable;
    }

    public function setIsCustomizable(bool $isCustomizable): static
    {
        $this->isCustomizable = $isCustomizable;
        return $this;
    }

    public function isInStock(): bool
    {
        return $this->isInStock;
    }

    public function setIsInStock(bool $isInStock): static
    {
        $this->isInStock = $isInStock;
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
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

    public function getMainImage(): ?Media
    {
        return $this->mainImage;
    }

    public function setMainImage(?Media $mainImage): static
    {
        $this->mainImage = $mainImage;
        return $this;
    }

    /**
     * @return Collection<int, ProductTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(ProductTranslation $translation): static
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setProduct($this);
        }
        return $this;
    }

    public function removeTranslation(ProductTranslation $translation): static
    {
        if ($this->translations->removeElement($translation)) {
            if ($translation->getProduct() === $this) {
                $translation->setProduct(null);
            }
        }
        return $this;
    }

    /**
     * Get translation for a specific locale
     */
    public function getTranslation(string $locale = 'en_US'): ?ProductTranslation
    {
        return $this->translations->get($locale);
    }

    /**
     * Get name for a specific locale
     */
    public function getName(string $locale = 'en_US'): string
    {
        $translation = $this->getTranslation($locale);
        return $translation?->getName() ?? '';
    }

    /**
     * Get description for a specific locale
     */
    public function getDescription(string $locale = 'en_US'): string
    {
        $translation = $this->getTranslation($locale);
        return $translation?->getDescription() ?? '';
    }

    /**
     * @return Collection<int, ProductMedia>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(ProductMedia $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setProduct($this);
        }
        return $this;
    }

    public function removeMedium(ProductMedia $medium): static
    {
        if ($this->media->removeElement($medium)) {
            if ($medium->getProduct() === $this) {
                $medium->setProduct(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, ProductOption>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(ProductOption $option): static
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->setProduct($this);
        }
        return $this;
    }

    public function removeOption(ProductOption $option): static
    {
        if ($this->options->removeElement($option)) {
            if ($option->getProduct() === $this) {
                $option->setProduct(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, ProductSpecification>
     */
    public function getSpecifications(): Collection
    {
        return $this->specifications;
    }

    public function addSpecification(ProductSpecification $specification): static
    {
        if (!$this->specifications->contains($specification)) {
            $this->specifications->add($specification);
            $specification->setProduct($this);
        }
        return $this;
    }

    public function removeSpecification(ProductSpecification $specification): static
    {
        if ($this->specifications->removeElement($specification)) {
            if ($specification->getProduct() === $this) {
                $specification->setProduct(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
