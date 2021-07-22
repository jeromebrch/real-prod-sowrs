<?php

namespace App\Entity;

use App\Repository\JobOfferRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=JobOfferRepository::class)
 *
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
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
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


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $NumberOfViews;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class)
     */
    private $department;

    /**
     * @ORM\ManyToOne (targetEntity=Region::class)
     */
    private $region;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $telecommuting;

    /**
     * @ORM\ManyToMany(targetEntity=Candidate::class, mappedBy="favoriteOffers")
     */
    private $interestedCandidates;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberAddress;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Regex(pattern="/^[0-9]{5}$/", message="Format non valide, merci de vérifier le code postal !")
     */
    private $postalCode;

    public function __construct(){
        $this->creationDate = new DateTime();
        $this->published = true;
        $this->interestedCandidates = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param mixed $department
     */
    public function setDepartment($department):self
    {
        $this->department = $department;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region):self
    {
        $this->region = $region;
        return $this;
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

    public function getNumberOfViews(): ?int
    {
        return $this->NumberOfViews;
    }

    public function setNumberOfViews(?int $NumberOfViews): self
    {
        $this->NumberOfViews = $NumberOfViews;

        return $this;
    }

    public function getTelecommuting(): ?bool
    {
        return $this->telecommuting;
    }

    public function setTelecommuting(?bool $telecommuting): self
    {
        $this->telecommuting = $telecommuting;

        return $this;
    }

    public function getInterestedCandidate(): ?Candidate
    {
        return $this->interestedCandidate;
    }

    public function setInterestedCandidate(?Candidate $interestedCandidate): self
    {
        $this->interestedCandidate = $interestedCandidate;

        return $this;
    }

    /**
     * @return Collection|Candidate[]
     */
    public function getInterestedCandidates(): Collection
    {
        return $this->interestedCandidates;
    }

    public function addInterestedCandidate(Candidate $interestedCandidate): self
    {
        if (!$this->interestedCandidates->contains($interestedCandidate)) {
            $this->interestedCandidates[] = $interestedCandidate;
            $interestedCandidate->addFavoriteOffer($this);
        }

        return $this;
    }

    public function removeInterestedCandidate(Candidate $interestedCandidate): self
    {
        if ($this->interestedCandidates->removeElement($interestedCandidate)) {
            $interestedCandidate->removeFavoriteOffer($this);
        }

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getNumberAddress(): ?int
    {
        return $this->numberAddress;
    }

    public function setNumberAddress(?int $numberAddress): self
    {
        $this->numberAddress = $numberAddress;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(?int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }
     
}
