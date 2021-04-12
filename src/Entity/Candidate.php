<?php
namespace App\Entity;

use App\Repository\CandidateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CandidateRepository::class)
 */
class Candidate extends User
{
    /**
     * @ORM\ManyToOne(targetEntity=Cv::class, inversedBy="candidate")
     */
    public $cv;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $currentRole;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $inspiration;

    /**
     * @ORM\ManyToOne(targetEntity=LevelStudy::class, inversedBy="candidates")
     */
    private $levelStudy;

    /**
     * @ORM\ManyToOne(targetEntity=LevelExperience::class, inversedBy="candidates")
     */
    private $levelExperience;

    /**
     * @ORM\ManyToOne(targetEntity=BusinessProfile::class, inversedBy="candidates")
     */
    private $businessProfile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=JobSearch::class, inversedBy="candidates",cascade={"PERSIST"})
     */
    private $jobSearch;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="candidates")
     */
    private $authorizedCountry;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="candidate")
     */
    private $department;

    /**
     * @ORM\OneToOne(targetEntity=SocialNetwork::class, inversedBy="candidate", cascade={"persist", "remove"})
     */
    private $socialNetwork;

    /**
     * @ORM\ManyToMany(targetEntity=Commitment::class, inversedBy="candidates")
     */
    private $commitments;

    /**
     * @ORM\ManyToOne(targetEntity=Cause::class, inversedBy="candidates")
     */
    private $mainCause;

    public function __construct()
    {
        parent::__construct();
        $this->commitments = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
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
        return  $this;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getRoles(): array
    {
        $roles[] = parent::getRoles();
        $roles = ['ROLE_USER','ROLE_CANDIDATE'];
        if($this->getEmail() == "kennouche.annelise@gmail.com"){ // todo : modifier pour avoir l'admin voulu
            $roles = ['ROLE_USER','ROLE_CANDIDATE', 'ROLE_ADMIN'];
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): User
    {
        return parent::setRoles($roles);
    }

    public function getCurrentRole(): ?string
    {
        return $this->currentRole;
    }

    public function setCurrentRole(?string $currentRole): self
    {
        $this->currentRole = $currentRole;

        return $this;
    }

    public function getInspiration(): ?string
    {
        return $this->inspiration;
    }

    public function setInspiration(?string $inspiration): self
    {
        $this->inspiration = $inspiration;

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


    public function getLevelExperience(): ?LevelExperience
    {
        return $this->levelExperience;
    }

    public function setLevelExperience(?LevelExperience $levelExperience): self
    {
        $this->levelExperience = $levelExperience;

        return $this;
    }

    public function getBusinessProfile(): ?BusinessProfile
    {
        return $this->businessProfile;
    }

    public function setBusinessProfile(?BusinessProfile $businessProfile): self
    {
        $this->businessProfile = $businessProfile;

        return $this;
    }


    public function getJobSearch(): ?JobSearch
    {
        return $this->jobSearch;
    }

    public function setJobSearch(?JobSearch $jobSearch): self
    {
        $this->jobSearch = $jobSearch;

        return $this;
    }

    public function getAuthorizedCountry(): ?Country
    {
        return $this->authorizedCountry;
    }

    public function setAuthorizedCountry(?Country $authorizedCountry): self
    {
        $this->authorizedCountry = $authorizedCountry;

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

    public function getCv(): ?Cv
    {
        return $this->cv;
    }

    public function setCv(?Cv $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function __toString()
    {
        return $this->firstname . ' ' . $this->lastname;
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

    public function getMainCause(): ?Cause
    {
        return $this->mainCause;
    }

    public function setMainCause(?Cause $mainCause): self
    {
        $this->mainCause = $mainCause;

        return $this;
    }

}

