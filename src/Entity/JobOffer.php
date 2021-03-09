<?php

namespace App\Entity;

use App\Repository\JobOfferRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobOfferRepository::class)
 */
class JobOffer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity=ContractType::class, inversedBy="jobOffers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contractType;

    /**
     * @ORM\ManyToOne(targetEntity=LevelExperience::class, inversedBy="jobOffers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $levelExperience;

    /**
     * @ORM\ManyToOne(targetEntity=LevelStudy::class, inversedBy="jobOffers")
     */
    private $levelStudy;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="jobOffers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $city;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity=Recruiter::class, inversedBy="jobOffers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entity;

    /**
     * @ORM\ManyToOne(targetEntity=BusinessProfile::class, inversedBy="jobOffers")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Remuneration::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $remuneration;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    public function __construct(){
        $this->creationDate = new DateTime();
        $this->published = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }
    

    public function getContractType(): ?ContractType
    {
        return $this->contractType;
    }

    public function setContractType(?ContractType $contractType): self
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getLevelExperience(): ?LevelExperience
    {
        return $this->levelExperience;
    }

    public function setLevelExperience(?LevelExperience $levelExperience): self
    {
        $this->levelExperience = $levelExperience;

        return $this;
    }

    public function getLevelStudy(): ?LevelStudy
    {
        return $this->levelStudy;
    }

    public function setLevelStudy(?LevelStudy $levelStudy): self
    {
        $this->levelStudy = $levelStudy;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCreationDate(): ?DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getEntity(): ?Recruiter
    {
        return $this->entity;
    }

    public function setEntity(?Recruiter $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    public function getCategory(): ?BusinessProfile
    {
        return $this->category;
    }

    public function setCategory(?BusinessProfile $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getRemuneration(): ?Remuneration
    {
        return $this->remuneration;
    }

    public function setRemuneration(?Remuneration $remuneration): self
    {
        $this->remuneration = $remuneration;

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }
     
}
