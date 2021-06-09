<?php

namespace App\Entity;

use App\Repository\RecruiterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RecruiterRepository::class)
 */
class Recruiter extends User
{

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $entityName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $reasonToBe;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $carbonFootPrint;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(
     *     pattern="/[0-9]/", message="Numéro d'activité invalide"
     * )
     */
    private $activityNumber;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $function;

    /**
     * @ORM\ManyToOne(targetEntity=LegalStatus::class, inversedBy="recruiters", cascade="persist")
     */
    private $legalStatus;

    /**
     * @ORM\ManyToOne(targetEntity=Cause::class, inversedBy="recruiters")
     */
    private $mainCause;
    
    /**
     * @ORM\ManyToMany(targetEntity=Cause::class, inversedBy="secondaryRecruiters")
     */
    private $secondaryCauses;

    /**
     * @ORM\OneToMany(targetEntity=JobOffer::class, mappedBy="entity", cascade={"remove"})
     */
    private $jobOffers;

    /**
     * @ORM\OneToOne(targetEntity=SocialNetwork::class, inversedBy="recruiter", cascade={"persist", "remove"})
     */
    private $socialNetwork;

    /**
     * @ORM\OneToMany(targetEntity=Recognition::class, mappedBy="recruiter", orphanRemoval=true)
     */
    private $recognitions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="recruiter")
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, cascade="persist")
     */
    private $region;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $carbonFootPrintProofFilename;

    /**
     * @ORM\ManyToMany(targetEntity=Commitment::class, inversedBy="recruiters")
     */
    private $commitments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $presentationVideoURL;

    /**
     * @ORM\ManyToMany(targetEntity=Candidate::class, inversedBy="interestedRecruiters")
     */
    private $favoriteCandidates;


   public function __construct()
   {
       parent::__construct();
       $this->secondaryCauses = new ArrayCollection();
       $this->jobOffers = new ArrayCollection();
       $this->recognitions = new ArrayCollection();
       $this->commitments = new ArrayCollection();
       $this->favoriteCandidates = new ArrayCollection();
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
    public function setDepartment($department): self
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
    public function setRegion($region): self
    {
        $this->region = $region;
        return $this;
    }

   public function setRoles(array $roles): User
   {
       return parent::setRoles($roles);
   }

   public function getRoles(): array
   {
       $roles[] = parent::getRoles();
       $roles = ['ROLE_USER','ROLE_RECRUITER'];
       if($this->getEmail() == "jerome.brch@gmail.com"){ // todo : modifier pour avoir l'admin voulu
           $roles = ['ROLE_USER','ROLE_CANDIDATE', 'ROLE_ADMIN'];
       }

       return array_unique($roles);
   }

    public function getCarbonFootPrint(): ?string
    {
        return $this->carbonFootPrint;
    }

    public function setCarbonFootPrint(?string $carbonFootPrint): self
    {
        $this->carbonFootPrint = $carbonFootPrint;

        return $this;
    }

    public function getEntityName(): ?string
    {
        return $this->entityName;
    }

    public function setEntityName(string $entityName): self
    {
        $this->entityName = $entityName;

        return $this;
    }

    public function getReasonToBe(): ?string
    {
        return $this->reasonToBe;
    }

    public function setReasonToBe(?string $reasonToBe): self
    {
        $this->reasonToBe = $reasonToBe;

        return $this;
    }

    public function getActivityNumber(): ?string
    {
        return $this->activityNumber;
    }

    public function setActivityNumber(string $activityNumber): self
    {
        $this->activityNumber = $activityNumber;

        return $this;
    }

    public function getFunction(): ?string
    {
        return $this->function;
    }

    public function setFunction(string $function): self
    {
        $this->function = $function;

        return $this;
    }

    public function getLegalStatus(): ?LegalStatus
    {
        return $this->legalStatus;
    }

    public function setLegalStatus(?LegalStatus $legalStatus): self
    {
        $this->legalStatus = $legalStatus;

        return $this;
    }

    public function getMainCause(): ?Cause
    {
        return $this->mainCause;
    }

    public function setMainCause(?Cause $mainCause): self
    {
        $this->mainCause = $mainCause;

        return $this;
    }

    /**
     * @return Collection|Cause[]
     */
    public function getSecondaryCauses(): Collection
    {
        return $this->secondaryCauses;
    }

    public function addSecondaryCause(Cause $secondaryCause): self
    {
        if (!$this->secondaryCauses->contains($secondaryCause)) {
            $this->secondaryCauses[] = $secondaryCause;
        }

        return $this;
    }

    public function removeSecondaryCause(Cause $secondaryCause): self
    {
        $this->secondaryCauses->removeElement($secondaryCause);

        return $this;
    }



    /**
     * @return Collection|JobOffer[]
     */
    public function getJobOffers(): Collection
    {
        return $this->jobOffers;
    }

    public function addJobOffer(JobOffer $jobOffer): self
    {
        if (!$this->jobOffers->contains($jobOffer)) {
            $this->jobOffers[] = $jobOffer;
            $jobOffer->setEntity($this);
        }

        return $this;
    }

    public function removeJobOffer(JobOffer $jobOffer): self
    {
        if ($this->jobOffers->removeElement($jobOffer)) {
            // set the owning side to null (unless already changed)
            if ($jobOffer->getEntity() === $this) {
                $jobOffer->setEntity(null);
            }
        }

        return $this;
    }

    public function getSocialNetwork(): ?SocialNetwork
    {
        return $this->socialNetwork;
    }

    public function setSocialNetwork(?SocialNetwork $socialNetwork): self
    {
        $this->socialNetwork = $socialNetwork;

        return $this;
    }

    /**
     * @return Collection|Recognition[]
     */
    public function getRecognitions(): Collection
    {
        return $this->recognitions;
    }

    public function addRecognition(Recognition $recognition): self
    {
        if (!$this->recognitions->contains($recognition)) {
            $this->recognitions[] = $recognition;
            $recognition->setRecruiter($this);
        }

        return $this;
    }

    public function removeRecognition(Recognition $recognition): self
    {
        if ($this->recognitions->removeElement($recognition)) {
            // set the owning side to null (unless already changed)
            if ($recognition->getRecruiter() === $this) {
                $recognition->setRecruiter(null);
            }
        }

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCarbonFootPrintProofFilename(): ?string
    {
        return $this->carbonFootPrintProofFilename;
    }

    public function setCarbonFootPrintProofFilename(?string $carbonFootPrintProofFilename): self
    {
        $this->carbonFootPrintProofFilename = $carbonFootPrintProofFilename;

        return $this;
    }

    /**
     * @return Collection|Commitment[]
     */
    public function getCommitments(): Collection
    {
        return $this->commitments;
    }

    public function addCommitment(Commitment $commitment): self
    {
        if (!$this->commitments->contains($commitment)) {
            $this->commitments[] = $commitment;
        }

        return $this;
    }

    public function removeCommitment(Commitment $commitment): self
    {
        $this->commitments->removeElement($commitment);

        return $this;
    }

    public function getPresentationVideoURL(): ?string
    {
        return $this->presentationVideoURL;
    }

    public function setPresentationVideoURL(?string $presentationVideoURL): self
    {
        $this->presentationVideoURL = $presentationVideoURL;

        return $this;
    }

    /**
     * @return Collection|Candidate[]
     */
    public function getFavoriteCandidates(): Collection
    {
        return $this->favoriteCandidates;
    }

    public function addFavoriteCandidate(Candidate $favoriteCandidate): self
    {
        if (!$this->favoriteCandidates->contains($favoriteCandidate)) {
            $this->favoriteCandidates[] = $favoriteCandidate;
        }

        return $this;
    }

    public function removeFavoriteCandidate(Candidate $favoriteCandidate): self
    {
        $this->favoriteCandidates->removeElement($favoriteCandidate);

        return $this;
    }

}
