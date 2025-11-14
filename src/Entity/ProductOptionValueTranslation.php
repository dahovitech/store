<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductOptionValueTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductOptionValueTranslationRepository::class)]
#[ORM\Table(name: 'product_option_value_translations')]
#[ORM\UniqueConstraint(name: 'unique_option_value_locale', columns: ['value_id', 'locale'])]
#[Gedmo\TranslationEntity(class: ProductOptionValueTranslation::class)]
class ProductOptionValueTranslation
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
    #[Assert\Length(min: 1, max: 200)]
    private string $displayValue;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: ProductOptionValue::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductOptionValue $value = null;

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

    public function getDisplayValue(): string
    {
        return $this->displayValue;
    }

    public function setDisplayValue(string $displayValue): static
    {
        $this->displayValue = $displayValue;
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

    public function getValue(): ?ProductOptionValue
    {
        return $this->value;
    }

    public function setValue(?ProductOptionValue $value): static
    {
        $this->value = $value;
        return $this;
    }

    public function __toString(): string
    {
        return $this->displayValue;
    }
}
