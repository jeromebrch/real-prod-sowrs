<?php
namespace App\Entity;

use App\Repository\CandidateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CandidateRepository::class)
 */
class Candidate extends User
{
    /**
     * @ORM\OneToOne(targetEntity=Cv::class)
     * @ORM\JoinColumn(nullable=true)
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
     * @ORM\ManyToOne(targetEntity=JobSearch::class, inversedBy="candidates",cascade={"PERSIST"})
     */
    private $jobSearch;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="candidates")
     */
    private $authorizedCountry;

    /**
     * @ORM\OneToOne(targetEntity=SocialNetwork::class, inversedBy="candidate", cascade={"persist", "remove"})
     */
    private $socialNetwork;

    public function getRoles(): array
    {
        $roles[] = parent::getRoles();
        $roles = ['ROLE_USER','ROLE_CANDIDATE'];

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

    public function __toString(){
        return $this->firstname.' '.$this->lastname;
    }

}

