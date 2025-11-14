<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductTranslationRepository::class)]
#[ORM\Table(name: 'product_translations')]
#[ORM\UniqueConstraint(name: 'product_language_unique', columns: ['product_id', 'language_id'])]
class ProductTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $conceptDesign = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $solutionsEspace = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $equipementsSpeciaux = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $materiauxBiosources = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $technologiesEcosopheres = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $equipementsEcologiques = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $cuisineZeroDechet = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $espaceNuitOptimise = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $salleDEauEco = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $systemeChauffageSophistique = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $cuisineDesign = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $salleDEauSpa = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $architectureModulaire = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $cuisinePremium = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $salonDetente = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $chambresOptimisees = null;

    #[ORM\Column(type: 'string', length: 500, nullable: true)]
    private ?string $seoTitle = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $seoDescription = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $metaKeywords = null;

    // Relations
    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(targetEntity: Language::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Language $language = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getConceptDesign(): ?string
    {
        return $this->conceptDesign;
    }

    public function setConceptDesign(?string $conceptDesign): static
    {
        $this->conceptDesign = $conceptDesign;
        return $this;
    }

    public function getSolutionsEspace(): ?string
    {
        return $this->solutionsEspace;
    }

    public function setSolutionsEspace(?string $solutionsEspace): static
    {
        $this->solutionsEspace = $solutionsEspace;
        return $this;
    }

    public function getEquipementsSpeciaux(): ?string
    {
        return $this->equipementsSpeciaux;
    }

    public function setEquipementsSpeciaux(?string $equipementsSpeciaux): static
    {
        $this->equipementsSpeciaux = $equipementsSpeciaux;
        return $this;
    }

    public function getMateriauxBiosources(): ?string
    {
        return $this->materiauxBiosources;
    }

    public function setMateriauxBiosources(?string $materiauxBiosources): static
    {
        $this->materiauxBiosources = $materiauxBiosources;
        return $this;
    }

    public function getTechnologiesEcosopheres(): ?string
    {
        return $this->technologiesEcosopheres;
    }

    public function setTechnologiesEcosopheres(?string $technologiesEcosopheres): static
    {
        $this->technologiesEcosopheres = $technologiesEcosopheres;
        return $this;
    }

    public function getEquipementsEcologiques(): ?string
    {
        return $this->equipementsEcologiques;
    }

    public function setEquipementsEcologiques(?string $equipementsEcologiques): static
    {
        $this->equipementsEcologiques = $equipementsEcologiques;
        return $this;
    }

    public function getCuisineZeroDechet(): ?string
    {
        return $this->cuisineZeroDechet;
    }

    public function setCuisineZeroDechet(?string $cuisineZeroDechet): static
    {
        $this->cuisineZeroDechet = $cuisineZeroDechet;
        return $this;
    }

    public function getEspaceNuitOptimise(): ?string
    {
        return $this->espaceNuitOptimise;
    }

    public function setEspaceNuitOptimise(?string $espaceNuitOptimise): static
    {
        $this->espaceNuitOptimise = $espaceNuitOptimise;
        return $this;
    }

    public function getSalleDEauEco(): ?string
    {
        return $this->salleDEauEco;
    }

    public function setSalleDEauEco(?string $salleDEauEco): static
    {
        $this->salleDEauEco = $salleDEauEco;
        return $this;
    }

    public function getSystemeChauffageSophistique(): ?string
    {
        return $this->systemeChauffageSophistique;
    }

    public function setSystemeChauffageSophistique(?string $systemeChauffageSophistique): static
    {
        $this->systemeChauffageSophistique = $systemeChauffageSophistique;
        return $this;
    }

    public function getCuisineDesign(): ?string
    {
        return $this->cuisineDesign;
    }

    public function setCuisineDesign(?string $cuisineDesign): static
    {
        $this->cuisineDesign = $cuisineDesign;
        return $this;
    }

    public function getSalleDEauSpa(): ?string
    {
        return $this->salleDEauSpa;
    }

    public function setSalleDEauSpa(?string $salleDEauSpa): static
    {
        $this->salleDEauSpa = $salleDEauSpa;
        return $this;
    }

    public function getArchitectureModulaire(): ?string
    {
        return $this->architectureModulaire;
    }

    public function setArchitectureModulaire(?string $architectureModulaire): static
    {
        $this->architectureModulaire = $architectureModulaire;
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

    public function getSalonDetente(): ?string
    {
        return $this->salonDetente;
    }

    public function setSalonDetente(?string $salonDetente): static
    {
        $this->salonDetente = $salonDetente;
        return $this;
    }

    public function getChambresOptimisees(): ?string
    {
        return $this->chambresOptimisees;
    }

    public function setChambresOptimisees(?string $chambresOptimisees): static
    {
        $this->chambresOptimisees = $chambresOptimisees;
        return $this;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function setSeoTitle(?string $seoTitle): static
    {
        $this->seoTitle = $seoTitle;
        return $this;
    }

    public function getSeoDescription(): ?string
    {
        return $this->seoDescription;
    }

    public function setSeoDescription(?string $seoDescription): static
    {
        $this->seoDescription = $seoDescription;
        return $this;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords(?string $metaKeywords): static
    {
        $this->metaKeywords = $metaKeywords;
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

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): static
    {
        $this->language = $language;
        return $this;
    }

    public function __toString(): string
    {
        return $this->name . ' (' . $this->language . ')';
    }
}