<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductOptionRepository::class)]
#[ORM\Table(name: 'product_options')]
#[Gedmo\TranslationEntity(class: ProductOptionTranslation::class)]
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
    private string $code;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    private string $type; // 'material', 'finishing', 'equipment', 'structure', 'energy'

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    private string $inputType; // 'select', 'radio', 'checkbox', 'color', 'number'

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $priceImpact = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isRequired = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isMultiple = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'integer')]
    #[Assert\PositiveOrZero]
    private int $sortOrder = 0;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $config = null; // additional configuration

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'create')]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Gedmo\Timestampable(on: 'update')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'options')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\OneToMany(targetEntity: ProductOptionTranslation::class, mappedBy: 'option', cascade: ['persist', 'remove'], indexBy: 'locale')]
    private Collection $translations;

    #[ORM\OneToMany(targetEntity: ProductOptionValue::class, mappedBy: 'option', cascade: ['persist', 'remove'])]
    private Collection $values;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->values = new ArrayCollection();
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getInputType(): string
    {
        return $this->inputType;
    }

    public function setInputType(string $inputType): static
    {
        $this->inputType = $inputType;
        return $this;
    }

    public function getPriceImpact(): ?string
    {
        return $this->priceImpact;
    }

    public function setPriceImpact(?string $priceImpact): static
    {
        $this->priceImpact = $priceImpact;
        return $this;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(bool $isRequired): static
    {
        $this->isRequired = $isRequired;
        return $this;
    }

    public function isMultiple(): bool
    {
        return $this->isMultiple;
    }

    public function setIsMultiple(bool $isMultiple): static
    {
        $this->isMultiple = $isMultiple;
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

    public function getConfig(): ?array
    {
        return $this->config;
    }

    public function setConfig(?array $config): static
    {
        $this->config = $config;
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return Collection<int, ProductOptionTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(ProductOptionTranslation $translation): static
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setOption($this);
        }
        return $this;
    }

    public function removeTranslation(ProductOptionTranslation $translation): static
    {
        if ($this->translations->removeElement($translation)) {
            if ($translation->getOption() === $this) {
                $translation->setOption(null);
            }
        }
        return $this;
    }

    /**
     * Get translation for a specific locale
     */
    public function getTranslation(string $locale = 'en_US'): ?ProductOptionTranslation
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
     * @return Collection<int, ProductOptionValue>
     */
    public function getValues(): Collection
    {
        return $this->values;
    }

    public function addValue(ProductOptionValue $value): static
    {
        if (!$this->values->contains($value)) {
            $this->values->add($value);
            $value->setOption($this);
        }
        return $this;
    }

    public function removeValue(ProductOptionValue $value): static
    {
        if ($this->values->removeElement($value)) {
            if ($value->getOption() === $this) {
                $value->setOption(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
