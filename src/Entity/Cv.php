<?php

namespace App\Entity;

use App\Repository\CvRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=CvRepository::class)
 * @Vich\Uploadable
 */
class Cv
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * REMARQUE: Ce n'est pas un champ mappé de métadonnées d'entité, juste une simple propriété.
     * @Vich\UploadableField (mapping = "cv", fileNameProperty = "CvName", size = "CvSize")
     * @var File | null
     */
    public $CvFile;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    public $CvName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $CvSize;

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
    public function getCvFile(): ?File
    {
        return $this->CvFile;
    }

    /**
     * @param File|UploadedFile|null $Cv
     */
    public function setCvFile(?File $Cv = null): void
    {
        $this->CvFile = $Cv;

        if (null !== $Cv) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return mixed
     */
    public function getCvName(): ?string
    {
        return $this->CvName;
    }

    /**
     * @param mixed $CvName
     */
    public function setCvName(?string $CvName): self
    {
        $this->CvName = $CvName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCvSize(): ?int
    {
        return $this->CvSize;
    }

    /**
     * @param mixed $CvSize
     */
    public function setCvSize(?int $CvSize): self
    {
        $this->CvSize = $CvSize;

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
        return serialize(array($this->id, $this->CvName));
    }
    public function unserialize($serialized) {
        list ($this->id, $this->CvName) = unserialize($serialized, array('allowed_classes' => false));
    }

}
