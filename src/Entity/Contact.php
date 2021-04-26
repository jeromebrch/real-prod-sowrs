<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(max="10")
     */
    private $telephone;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $destinataire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File()
     */
    private $file;

    /**
     * REMARQUE: Ce n'est pas un champ mappé de métadonnées d'entité, juste une simple propriété.
     * @Vich\UploadableField (mapping = "file")
     * @var File | null
     */
    private $fichier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File()
     */
    private $otherFile;

    /**
     * REMARQUE: Ce n'est pas un champ mappé de métadonnées d'entité, juste une simple propriété.
     * @Vich\UploadableField (mapping = "otherFile")
     * @var File | null
     */
    private $autreFichier;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDestinataire(): ?string
    {
        return $this->destinataire;
    }

    public function setDestinataire(string $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(?string $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getAutreFichier(): ?string
    {
        return $this->autreFichier;
    }

    public function setAutreFichier(?string $autreFichier): self
    {
        $this->autreFichier = $autreFichier;

        return $this;
    }
}
