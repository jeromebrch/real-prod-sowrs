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
     * @Vich\UploadableField (mapping = "cv", fileNameProperty = "CvName", size = "CvSize")
     * @var File | null
     */
    public $cvFile;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    public $cvName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cvSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=Message::class)
     */
    private $message;

    /**
     * @ORM\OneToMany(targetEntity=Favorite::class, mappedBy="cv", cascade="remove")
     */
    private $favorite;

    /**
     * @ORM\OneToMany(targetEntity=Candidate::class, mappedBy="cv")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidate;

    /**
     * @return mixed
     */
    public function getCandidate()
    {
        return $this->candidate;
    }

    /**
     * @param mixed $candidate
     */
    public function setCandidate($candidate):self
    {
        $this->candidate = $candidate;
        return $this;
    }



    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * @param mixed $favorite
     */
    public function setFavorite($favorite): self
    {
        $this->favorite = $favorite;
        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return File|null
     */
    public function getcvFile(): ?File
    {
        return $this->cvFile;
    }

    /**
     * @param File|UploadedFile|null $Cv
     */
    public function setCvFile(?File $cv = null): self
    {
        $this->cvFile = $cv;

        if (null !== $cv) {
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCvName(): ?string
    {
        return $this->cvName;
    }

    /**
     * @param mixed $CvName
     */
    public function setCvName(?string $cvName): self
    {
        $this->cvName = $cvName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCvSize(): ?int
    {
        return $this->cvSize;
    }

    /**
     * @param mixed $cvSize
     */
    public function setCvSize(?int $cvSize): self
    {
        $this->cvSize = $cvSize;

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

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

}
