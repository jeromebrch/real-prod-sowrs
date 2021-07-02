<?php

namespace App\Entity;

use App\Repository\RecognitionRepository;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\String_;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RecognitionRepository::class)
 */
class Recognition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_add_action_recruiter"})
     */
    private $id;


    /**
     * @ORM\Column(type="text")
     * @Groups({"get_add_action_recruiter"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Cause::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     * @Groups({"get_add_action_recruiter"})
     */
    private $cause;

    /**
     * @var String
     */
    private $currentActivity;

    /**
     * @ORM\ManyToOne(targetEntity=Recruiter::class, inversedBy="recognitions")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups({"get_add_action_recruiter"})
     */
    private $recruiter;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *      max = 280,
     *      maxMessage = "Merci d'indiquer une description d'une longueur de 280 caractères maximum"
     * )
     */
    private $text;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCause(): ?Cause
    {
        return $this->cause;
    }

    public function setCause(?Cause $cause): self
    {
        $this->cause = $cause;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return String
     */
    public function getCurrentActivity(): string
    {
        return $this->currentActivity;
    }

    /**
     * @param String $currentActivity
     */
    public function setCurrentActivity(string $currentActivity): void
    {
        $this->currentActivity = $currentActivity;
    }

    public function getRecruiter(): ?Recruiter
    {
        return $this->recruiter;
    }

    public function setRecruiter(?Recruiter $recruiter): self
    {
        $this->recruiter = $recruiter;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    
}
