<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductOptionTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductOptionTranslationRepository::class)]
#[ORM\Table(name: 'product_option_translations')]
#[ORM\UniqueConstraint(name: 'unique_option_locale', columns: ['option_id', 'locale'])]
#[Gedmo\TranslationEntity(class: ProductOptionTranslation::class)]
class ProductOptionTranslation
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
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $helpText = null;

    #[ORM\ManyToOne(targetEntity: ProductOption::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProductOption $option = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getHelpText(): ?string
    {
        return $this->helpText;
    }

    public function setHelpText(?string $helpText): static
    {
        $this->helpText = $helpText;
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

    public function __toString(): string
    {
        return $this->name;
    }
}
