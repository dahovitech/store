<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductOptionValueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductOptionValueRepository::class)]
#[ORM\Table(name: 'product_option_values')]
class ProductOptionValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 100)]
    private string $value;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $priceImpact = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $deliveryTimeImpact = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\PositiveOrZero]
    private ?int $sortOrder = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $isDefault = false;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $config = null; // additional configuration

    #[ORM\ManyToOne(targetEntity: ProductOption::class, inversedBy: 'values')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductOption $option = null;

    #[ORM\OneToMany(targetEntity: ProductOptionValueTranslation::class, mappedBy: 'value', cascade: ['persist', 'remove'], indexBy: 'locale')]
    private Collection $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
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

    public function getDeliveryTimeImpact(): ?string
    {
        return $this->deliveryTimeImpact;
    }

    public function setDeliveryTimeImpact(?string $deliveryTimeImpact): static
    {
        $this->deliveryTimeImpact = $deliveryTimeImpact;
        return $this;
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(?int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;
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

    public function getConfig(): ?array
    {
        return $this->config;
    }

    public function setConfig(?array $config): static
    {
        $this->config = $config;
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

    /**
     * @return Collection<int, ProductOptionValueTranslation>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(ProductOptionValueTranslation $translation): static
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setValue($this);
        }
        return $this;
    }

    public function removeTranslation(ProductOptionValueTranslation $translation): static
    {
        if ($this->translations->removeElement($translation)) {
            if ($translation->getValue() === $this) {
                $translation->setValue(null);
            }
        }
        return $this;
    }

    /**
     * Get translation for a specific locale
     */
    public function getTranslation(string $locale = 'en_US'): ?ProductOptionValueTranslation
    {
        return $this->translations->get($locale);
    }

    /**
     * Get display value for a specific locale
     */
    public function getDisplayValue(string $locale = 'en_US'): string
    {
        $translation = $this->getTranslation($locale);
        return $translation?->getDisplayValue() ?? $this->value;
    }

    /**
     * Get description for a specific locale
     */
    public function getDescription(string $locale = 'en_US'): string
    {
        $translation = $this->getTranslation($locale);
        return $translation?->getDescription() ?? '';
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
