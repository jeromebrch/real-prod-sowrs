<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 * @Vich\Uploadable
 */
class Picture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * REMARQUE: Ce n'est pas un champ mappé de métadonnées d'entité, juste une simple propriété.
     * @Vich\UploadableField (mapping = "profils", fileNameProperty = "pictureName", size = "pictureSize")
     * @var File | null
     */
    private $pictureFile;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $pictureName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pictureSize;

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
    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    /**
     * @param File|UploadedFile|null $picture
     */
    public function setPictureFile(?File $picture = null): void
    {
        $this->pictureFile = $picture;

        if (null !== $picture) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return mixed
     */
    public function getPictureName(): ?string
    {
        return $this->pictureName;
    }

    /**
     * @param mixed $pictureName
     */
    public function setPictureName(?string $pictureName): self
    {
        $this->pictureName = $pictureName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPictureSize(): ?int
    {
        return $this->pictureSize;
    }

    /**
     * @param mixed $pictureSize
     */
    public function setPictureSize(?int $pictureSize): self
    {
        $this->pictureSize = $pictureSize;

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
        return serialize(array($this->id, $this->pictureName));
    }
    public function unserialize($serialized) {
        list ($this->id, $this->pictureName) = unserialize($serialized, array('allowed_classes' => false));
    }

}
