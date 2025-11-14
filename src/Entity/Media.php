<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\MediaRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;



#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Media
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fileName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $alt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $extension = null;

    private $file;

    private $tempFilename;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $type = null; // 'main_image', 'gallery', 'documentation', 'video', etc.

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $fileSize = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $width = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $height = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $mimeType = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $metadata = null; // Données EXIF, GPS, etc.

    #[ORM\Column(type: 'integer')]
    private int $sortOrder = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;

    // Relations avec les produits
    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'gallery')]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function setFile(File $file)
    {
        $this->file = $file;
        if (null !== $this->fileName) {
            $this->tempFilename = $this->fileName;

            $this->fileName = null;
            $this->alt = null;
            $this->extension = null;
        }
    }

    public function getFile()
    {
        return $this->file;
    }



    #[ORM\PrePersist()]
    #[ORM\PreUpdate()]
    public function preUpload()
    {
        if (null === $this->file) {
            return;
        }
        if ($this->file->guessExtension()) {

            $this->fileName = uniqid() . '.' . $this->file->guessExtension();
            $this->extension = $this->file->guessExtension();
        }

        $this->alt = $this->file->getClientOriginalName();
    }


    #[ORM\PostPersist()]
    #[ORM\PostUpdate()]
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        if (null !== $this->tempFilename) {
            $oldFile = $this->getUploadRootDir() . DIRECTORY_SEPARATOR . $this->id . '.' . $this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        $this->file->move($this->getUploadRootDir(), $this->getFileName());
        $this->file = null;
    }


    #[ORM\PreRemove()]
    public function preRemoveUpload()
    {
        $this->tempFilename = $this->getUploadRootDir() . DIRECTORY_SEPARATOR . $this->id . '.' . $this->fileName;
    }


    #[ORM\PostRemove()]
    public function removeUpload()
    {
        if (file_exists($this->tempFilename)) {
            unlink($this->tempFilename);
        }
    }

    public function getUploadDir()
    {
        return 'uploads/media';
    }

    protected function getUploadRootDir()
    {
        return __DIR__ . '/../../public/' . $this->getUploadDir();
    }

    public function getWebPath()
    {
        return $this->getUploadDir() . '/' . $this->getFileName();
    }

    /**
     * Get the value of fileName
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set the value of fileName
     *
     * @return  self
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function __toString(): string
    {
        return $this->alt ?? $this->fileName ?? 'Media #' . ($this->id ?? 'new');
    }

    public function getUrl(): string
    {
        return '/' . $this->getWebPath();
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setFileSize(?int $fileSize): static
    {
        $this->fileSize = $fileSize;
        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): static
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): static
    {
        $this->height = $height;
        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): static
    {
        $this->mimeType = $mimeType;
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

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;
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
            $product->addGallery($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeGallery($this);
        }

        return $this;
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSize(): string
    {
        if (!$this->fileSize) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        $fileSize = $this->fileSize;

        while ($fileSize >= 1024 && $unitIndex < count($units) - 1) {
            $fileSize /= 1024;
            $unitIndex++;
        }

        return round($fileSize, 2) . ' ' . $units[$unitIndex];
    }

    /**
     * Check if media is an image
     */
    public function isImage(): bool
    {
        return in_array($this->mimeType, [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'
        ]);
    }

    /**
     * Get aspect ratio
     */
    public function getAspectRatio(): ?float
    {
        if (!$this->width || !$this->height) {
            return null;
        }

        return round($this->width / $this->height, 2);
    }
}
