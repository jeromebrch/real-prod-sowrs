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
     * @ORM\ManyToOne(targetEntity=LegalStatus::class, inversedBy="recruiters")
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
     * @ORM\OneToMany(targetEntity=JobOffer::class, mappedBy="entity")
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



   public function __construct()
   {
       parent::__construct();
       $this->secondaryCauses = new ArrayCollection();
       $this->jobOffers = new ArrayCollection();
       $this->recognitions = new ArrayCollection();
   }

   public function setRoles(array $roles): User
   {
       return parent::setRoles($roles);
   }

   public function getRoles(): array
   {
       $roles[] = parent::getRoles();
       $roles = ['ROLE_USER','ROLE_RECRUITER'];

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

}
