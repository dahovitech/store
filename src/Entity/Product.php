<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    private string $sku;

    #[ORM\Column(type: 'string', length: 150, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 150)]
    private string $slug;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    private string $price;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $originalPrice = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull]
    #[Assert\PositiveOrZero]
    private int $surfaceHabitable;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $surfaceTerrasse = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $dimensionsExterieures = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $nbPieces = null;

    #[ORM\Column(type: 'decimal', precision: 3, scale: 1, nullable: true)]
    private ?string $hauteurSousPlafond = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $materiauxStructure = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $materiauxConstruction = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $equipementsInclus = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $performancesEnergetiques = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $classeEnergetique = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $tempsMontage = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $autonomieEnergetique = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $avantagesSpecifiques = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $specificationsTechniques = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $architectureInnovante = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $cuisinePremium = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $equipementsConfort = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $systemeDomotique = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $certifications = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $imagesAssociees = null;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2, nullable: true)]
    private ?string $prixM2 = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $surfaceUtile = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $typeAssemblage = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $niveauAutonomie = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $donneesComparatif = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'boolean')]
    private bool $isFeatured = false;

    #[ORM\Column(type: 'integer')]
    private int $sortOrder = 0;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    // Relations
    #[ORM\ManyToOne(targetEntity: ProductCategory::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductCategory $category = null;

    #[ORM\OneToOne(targetEntity: Media::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Media $mainImage = null;

    #[ORM\ManyToMany(targetEntity: Media::class, cascade: ['persist'])]
    #[ORM\JoinTable(name: 'product_gallery')]
    private Collection $gallery;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductTranslation::class, cascade: ['persist', 'remove'])]
    private Collection $translations;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductConfiguration::class, cascade: ['persist', 'remove'])]
    private Collection $configurations;

    #[ORM\ManyToMany(targetEntity: ProductOption::class, mappedBy: 'products')]
    private Collection $availableOptions;

    public function __construct()
    {
        $this->gallery = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->configurations = new ArrayCollection();
        $this->availableOptions = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): static
    {
        $this->sku = $sku;
        return $this;
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

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getOriginalPrice(): ?string
    {
        return $this->originalPrice;
    }

    public function setOriginalPrice(?string $originalPrice): static
    {
        $this->originalPrice = $originalPrice;
        return $this;
    }

    public function getSurfaceHabitable(): int
    {
        return $this->surfaceHabitable;
    }

    public function setSurfaceHabitable(int $surfaceHabitable): static
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

    public function getDimensionsExterieures(): ?string
    {
        return $this->dimensionsExterieures;
    }

    public function setDimensionsExterieures(?string $dimensionsExterieures): static
    {
        $this->dimensionsExterieures = $dimensionsExterieures;
        return $this;
    }

    public function getNbPieces(): ?int
    {
        return $this->nbPieces;
    }

    public function setNbPieces(?int $nbPieces): static
    {
        $this->nbPieces = $nbPieces;
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

    public function getMateriauxStructure(): ?string
    {
        return $this->materiauxStructure;
    }

    public function setMateriauxStructure(?string $materiauxStructure): static
    {
        $this->materiauxStructure = $materiauxStructure;
        return $this;
    }

    public function getMateriauxConstruction(): ?string
    {
        return $this->materiauxConstruction;
    }

    public function setMateriauxConstruction(?string $materiauxConstruction): static
    {
        $this->materiauxConstruction = $materiauxConstruction;
        return $this;
    }

    public function getEquipementsInclus(): ?string
    {
        return $this->equipementsInclus;
    }

    public function setEquipementsInclus(?string $equipementsInclus): static
    {
        $this->equipementsInclus = $equipementsInclus;
        return $this;
    }

    public function getPerformancesEnergetiques(): ?string
    {
        return $this->performancesEnergetiques;
    }

    public function setPerformancesEnergetiques(?string $performancesEnergetiques): static
    {
        $this->performancesEnergetiques = $performancesEnergetiques;
        return $this;
    }

    public function getClasseEnergetique(): ?string
    {
        return $this->classeEnergetique;
    }

    public function setClasseEnergetique(?string $classeEnergetique): static
    {
        $this->classeEnergetique = $classeEnergetique;
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

    public function getAutonomieEnergetique(): ?string
    {
        return $this->autonomieEnergetique;
    }

    public function setAutonomieEnergetique(?string $autonomieEnergetique): static
    {
        $this->autonomieEnergetique = $autonomieEnergetique;
        return $this;
    }

    public function getAvantagesSpecifiques(): ?string
    {
        return $this->avantagesSpecifiques;
    }

    public function setAvantagesSpecifiques(?string $avantagesSpecifiques): static
    {
        $this->avantagesSpecifiques = $avantagesSpecifiques;
        return $this;
    }

    public function getSpecificationsTechniques(): ?string
    {
        return $this->specificationsTechniques;
    }

    public function setSpecificationsTechniques(?string $specificationsTechniques): static
    {
        $this->specificationsTechniques = $specificationsTechniques;
        return $this;
    }

    public function getArchitectureInnovante(): ?string
    {
        return $this->architectureInnovante;
    }

    public function setArchitectureInnovante(?string $architectureInnovante): static
    {
        $this->architectureInnovante = $architectureInnovante;
        return $this;
    }

    public function getCuisinePremium(): ?string
    {
        return $this->cuisinePremium;
    }

    public function setCuisinePremium(?string $cuisinePremium): static
    {
        $this->cuisinePremium = $cuisinePremium;
        return $this;
    }

    public function getEquipementsConfort(): ?string
    {
        return $this->equipementsConfort;
    }

    public function setEquipementsConfort(?string $equipementsConfort): static
    {
        $this->equipementsConfort = $equipementsConfort;
        return $this;
    }

    public function getSystemeDomotique(): ?string
    {
        return $this->systemeDomotique;
    }

    public function setSystemeDomotique(?string $systemeDomotique): static
    {
        $this->systemeDomotique = $systemeDomotique;
        return $this;
    }

    public function getCertifications(): ?array
    {
        return $this->certifications;
    }

    public function setCertifications(?array $certifications): static
    {
        $this->certifications = $certifications;
        return $this;
    }

    public function getImagesAssociees(): ?array
    {
        return $this->imagesAssociees;
    }

    public function setImagesAssociees(?array $imagesAssociees): static
    {
        $this->imagesAssociees = $imagesAssociees;
        return $this;
    }

    public function getPrixM2(): ?string
    {
        return $this->prixM2;
    }

    public function setPrixM2(?string $prixM2): static
    {
        $this->prixM2 = $prixM2;
        return $this;
    }

    public function getSurfaceUtile(): ?int
    {
        return $this->surfaceUtile;
    }

    public function setSurfaceUtile(?int $surfaceUtile): static
    {
        $this->surfaceUtile = $surfaceUtile;
        return $this;
    }

    public function getTypeAssemblage(): ?string
    {
        return $this->typeAssemblage;
    }

    public function setTypeAssemblage(?string $typeAssemblage): static
    {
        $this->typeAssemblage = $typeAssemblage;
        return $this;
    }

    public function getNiveauAutonomie(): ?string
    {
        return $this->niveauAutonomie;
    }

    public function setNiveauAutonomie(?string $niveauAutonomie): static
    {
        $this->niveauAutonomie = $niveauAutonomie;
        return $this;
    }

    public function getDonneesComparatif(): ?array
    {
        return $this->donneesComparatif;
    }

    public function setDonneesComparatif(?array $donneesComparatif): static
    {
        $this->donneesComparatif = $donneesComparatif;
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
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
     * @return Collection<int, Media>
     */
    public function getGallery(): Collection
    {
        return $this->gallery;
    }

    public function addGallery(Media $gallery): static
    {
        if (!$this->gallery->contains($gallery)) {
            $this->gallery->add($gallery);
        }

        return $this;
    }

    public function removeGallery(Media $gallery): static
    {
        $this->gallery->removeElement($gallery);
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
     * Get translation for a specific language
     */
    public function getTranslation(Language $language): ?ProductTranslation
    {
        foreach ($this->translations as $translation) {
            if ($translation->getLanguage() === $language) {
                return $translation;
            }
        }
        return null;
    }

    /**
     * Get translation by language code
     */
    public function getTranslationByCode(string $languageCode): ?ProductTranslation
    {
        foreach ($this->translations as $translation) {
            if ($translation->getLanguage()->getCode() === $languageCode) {
                return $translation;
            }
        }
        return null;
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
            $configuration->setProduct($this);
        }

        return $this;
    }

    public function removeConfiguration(ProductConfiguration $configuration): static
    {
        if ($this->configurations->removeElement($configuration)) {
            if ($configuration->getProduct() === $this) {
                $configuration->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductOption>
     */
    public function getAvailableOptions(): Collection
    {
        return $this->availableOptions;
    }

    public function addAvailableOption(ProductOption $availableOption): static
    {
        if (!$this->availableOptions->contains($availableOption)) {
            $this->availableOptions->add($availableOption);
            $availableOption->addProduct($this);
        }

        return $this;
    }

    public function removeAvailableOption(ProductOption $availableOption): static
    {
        if ($this->availableOptions->removeElement($availableOption)) {
            $availableOption->removeProduct($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        $defaultTranslation = $this->getTranslationByCode('fr');
        return $defaultTranslation ? $defaultTranslation->getName() : $this->sku;
    }
}