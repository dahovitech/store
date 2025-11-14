<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductTranslationRepository::class)]
#[ORM\Table(name: 'product_translations')]
#[ORM\UniqueConstraint(name: 'unique_product_locale', columns: ['product_id', 'locale'])]
#[Gedmo\TranslationEntity(class: ProductTranslation::class)]
class ProductTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 10)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 10)]
    private string $locale;

    #[ORM\Column(type: 'string', length: 200)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 200)]
    private string $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $conceptDesign = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $materiauxConstruction = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $equipementsSpeciaux = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $cuisineEspace = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $espaceNuit = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $salleEau = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $solutionEspaceOptimise = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $cuisinePremium = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $systemeDomotique = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $equipementsConfort = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $architectureOptimale = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $cuisineEcot = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $equipementsEcologiques = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $architectureInnovante = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $equipementsPremium = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $systemeChauffage = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $cuisineDesign = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $salleEauSpa = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $structureRenforcee = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $finitionsHautGamme = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $imagesAssociees = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $avantages = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $certificationsLabels = null;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): static
    {
        $this->locale = $locale;
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

    public function getConceptDesign(): ?string
    {
        return $this->conceptDesign;
    }

    public function setConceptDesign(?string $conceptDesign): static
    {
        $this->conceptDesign = $conceptDesign;
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

    public function getEquipementsSpeciaux(): ?string
    {
        return $this->equipementsSpeciaux;
    }

    public function setEquipementsSpeciaux(?string $equipementsSpeciaux): static
    {
        $this->equipementsSpeciaux = $equipementsSpeciaux;
        return $this;
    }

    public function getCuisineEspace(): ?string
    {
        return $this->cuisineEspace;
    }

    public function setCuisineEspace(?string $cuisineEspace): static
    {
        $this->cuisineEspace = $cuisineEspace;
        return $this;
    }

    public function getEspaceNuit(): ?string
    {
        return $this->espaceNuit;
    }

    public function setEspaceNuit(?string $espaceNuit): static
    {
        $this->espaceNuit = $espaceNuit;
        return $this;
    }

    public function getSalleEau(): ?string
    {
        return $this->salleEau;
    }

    public function setSalleEau(?string $salleEau): static
    {
        $this->salleEau = $salleEau;
        return $this;
    }

    public function getSolutionEspaceOptimise(): ?string
    {
        return $this->solutionEspaceOptimise;
    }

    public function setSolutionEspaceOptimise(?string $solutionEspaceOptimise): static
    {
        $this->solutionEspaceOptimise = $solutionEspaceOptimise;
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

    public function getSystemeDomotique(): ?string
    {
        return $this->systemeDomotique;
    }

    public function setSystemeDomotique(?string $systemeDomotique): static
    {
        $this->systemeDomotique = $systemeDomotique;
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

    public function getArchitectureOptimale(): ?string
    {
        return $this->architectureOptimale;
    }

    public function setArchitectureOptimale(?string $architectureOptimale): static
    {
        $this->architectureOptimale = $architectureOptimale;
        return $this;
    }

    public function getCuisineEcot(): ?string
    {
        return $this->cuisineEcot;
    }

    public function setCuisineEcot(?string $cuisineEcot): static
    {
        $this->cuisineEcot = $cuisineEcot;
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

    public function getArchitectureInnovante(): ?string
    {
        return $this->architectureInnovante;
    }

    public function setArchitectureInnovante(?string $architectureInnovante): static
    {
        $this->architectureInnovante = $architectureInnovante;
        return $this;
    }

    public function getEquipementsPremium(): ?string
    {
        return $this->equipementsPremium;
    }

    public function setEquipementsPremium(?string $equipementsPremium): static
    {
        $this->equipementsPremium = $equipementsPremium;
        return $this;
    }

    public function getSystemeChauffage(): ?string
    {
        return $this->systemeChauffage;
    }

    public function setSystemeChauffage(?string $systemeChauffage): static
    {
        $this->systemeChauffage = $systemeChauffage;
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

    public function getSalleEauSpa(): ?string
    {
        return $this->salleEauSpa;
    }

    public function setSalleEauSpa(?string $salleEauSpa): static
    {
        $this->salleEauSpa = $salleEauSpa;
        return $this;
    }

    public function getStructureRenforcee(): ?string
    {
        return $this->structureRenforcee;
    }

    public function setStructureRenforcee(?string $structureRenforcee): static
    {
        $this->structureRenforcee = $structureRenforcee;
        return $this;
    }

    public function getFinitionsHautGamme(): ?string
    {
        return $this->finitionsHautGamme;
    }

    public function setFinitionsHautGamme(?string $finitionsHautGamme): static
    {
        $this->finitionsHautGamme = $finitionsHautGamme;
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

    public function getAvantages(): ?array
    {
        return $this->avantages;
    }

    public function setAvantages(?array $avantages): static
    {
        $this->avantages = $avantages;
        return $this;
    }

    public function getCertificationsLabels(): ?array
    {
        return $this->certificationsLabels;
    }

    public function setCertificationsLabels(?array $certificationsLabels): static
    {
        $this->certificationsLabels = $certificationsLabels;
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

    public function __toString(): string
    {
        return $this->name;
    }
}
