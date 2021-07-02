<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * Class Message
 * @package App\Entity
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="message")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="receivedMessages")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $userRecipient;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sendedMessages")
     * * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $userSender;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $subject;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $body;

    /**
     * @ORM\OneToOne(targetEntity=Cv::class)
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    public $cv;

    /**
     * REMARQUE: Ce n'est pas un champ mappé de métadonnées d'entité, juste une simple propriété.
     * @Vich\UploadableField (mapping = "cv", fileNameProperty = "cvName", size = "cvSize")
     * @var File | null
     */
    private $cvFile;

    /**
     * @ORM\OneToOne(targetEntity=Media::class)
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    public $media;

    /**
     * REMARQUE: Ce n'est pas un champ mappé de métadonnées d'entité, juste une simple propriété.
     * @Vich\UploadableField (mapping = "media", fileNameProperty = "mediaName", size = "mediaSize")
     * @var File | null
     */
    private $mediaFile;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $state;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getUserRecipient(): ?User
    {
        return $this->userRecipient;
    }

    public function setUserRecipient(?User $userRecipient): self
    {
        $this->userRecipient = $userRecipient;

        return $this;
    }

    public function getUserSender(): ?User
    {
        return $this->userSender;
    }

    public function setUserSender(?User $userSender): self
    {
        $this->userSender = $userSender;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * @param mixed $cv
     */
    public function setCv($cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getCvFile(): ?File
    {
        return $this->cvFile;
    }

    /**
     * @param File|null $cvFile
     */
    public function setCvFile(?File $cvFile): void
    {
        $this->cvFile = $cvFile;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param mixed $media
     */
    public function setMedia($media): self
    {
        $this->media = $media;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getMediaFile(): ?File
    {
        return $this->mediaFile;
    }

    /**
     * @param File|null $mediaFile
     */
    public function setMediaFile(?File $mediaFile): void
    {
        $this->mediaFile = $mediaFile;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


}
