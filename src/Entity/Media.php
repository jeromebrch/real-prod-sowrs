<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 * @Vich\Uploadable
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * REMARQUE: Ce n'est pas un champ mappé de métadonnées d'entité, juste une simple propriété.
     * @Vich\UploadableField (mapping = "media", fileNameProperty = "MediaName", size = "MediaSize")
     * @var File | null
     */
    private $MediaFile;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $MediaName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $MediaSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;


    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return File|null
     */
    public function getMediaFile(): ?File
    {
        return $this->MediaFile;
    }

    /**
     * @param File|UploadedFile|null $Media
     */
    public function setMediaFile(?File $Media = null): void
    {
        $this->MediaFile = $Media;

        if (null !== $Media) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return mixed
     */
    public function getMediaName(): ?string
    {
        return $this->MediaName;
    }

    /**
     * @param mixed $MediaName
     */
    public function setMediaName(?string $MediaName): self
    {
        $this->MediaName = $MediaName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMediaSize(): ?int
    {
        return $this->MediaSize;
    }

    /**
     * @param mixed $MediaSize
     */
    public function setMediaSize(?int $MediaSize): self
    {
        $this->MediaSize = $MediaSize;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function serialize() {
        return serialize(array($this->id, $this->MediaName));
    }
    public function unserialize($serialized) {
        list ($this->id, $this->MediaName) = unserialize($serialized, array('allowed_classes' => false));
    }

}
