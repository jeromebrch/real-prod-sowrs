<?php

namespace App\Entity;

use App\Repository\CauseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CauseRepository::class)
 */
class Cause
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_add_action_recruiter"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Groups({"get_add_action_recruiter"})
     */
    private $text;

    /**
     * @ORM\OneToMany(targetEntity=Recruiter::class, mappedBy="mainCause")
     */
    private $recruiters;

    /**
     * @ORM\ManyToMany(targetEntity=Recruiter::class, mappedBy="secondaryCauses")
     */
    private $secondaryRecruiters;

    /**
     * @ORM\OneToMany(targetEntity=JobSearch::class, mappedBy="cause")
     */
    private $jobSearches;



    

    public function __construct()
    {
        $this->recruiters = new ArrayCollection();
        $this->secondaryRecruiters = new ArrayCollection();
        $this->recognitions = new ArrayCollection();
        $this->candidates = new ArrayCollection();
        $this->jobSearches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|Recruiter[]
     */
    public function getRecruiters(): Collection
    {
        return $this->recruiters;
    }

    public function addRecruiter(Recruiter $recruiter): self
    {
        if (!$this->recruiters->contains($recruiter)) {
            $this->recruiters[] = $recruiter;
            $recruiter->setMainCause($this);
        }

        return $this;
    }

    public function removeRecruiter(Recruiter $recruiter): self
    {
        if ($this->recruiters->removeElement($recruiter)) {
            // set the owning side to null (unless already changed)
            if ($recruiter->getMainCause() === $this) {
                $recruiter->setMainCause(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Recruiter[]
     */
    public function getSecondaryRecruiters(): Collection
    {
        return $this->secondaryRecruiters;
    }

    public function addSecondaryRecruiter(Recruiter $secondaryRecruiter): self
    {
        if (!$this->secondaryRecruiters->contains($secondaryRecruiter)) {
            $this->secondaryRecruiters[] = $secondaryRecruiter;
        }

        return $this;
    }

    public function removeSecondaryRecruiter(Recruiter $secondaryRecruiter): self
    {
        $this->secondaryRecruiters->removeElement($secondaryRecruiter);

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
            $recognition->setCause($this);
        }

        return $this;
    }

    public function removeRecognition(Recognition $recognition): self
    {
        if ($this->recognitions->removeElement($recognition)) {
            // set the owning side to null (unless already changed)
            if ($recognition->getCause() === $this) {
                $recognition->setCause(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Candidate[]
     */
    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function addCandidate(Candidate $candidate): self
    {
        if (!$this->candidates->contains($candidate)) {
            $this->candidates[] = $candidate;
            $candidate->setCause($this);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        if ($this->candidates->removeElement($candidate)) {
            // set the owning side to null (unless already changed)
            if ($candidate->getCause() === $this) {
                $candidate->setCause(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|JobSearch[]
     */
    public function getJobSearches(): Collection
    {
        return $this->jobSearches;
    }

    public function addJobSearch(JobSearch $jobSearch): self
    {
        if (!$this->jobSearches->contains($jobSearch)) {
            $this->jobSearches[] = $jobSearch;
            $jobSearch->setCause($this);
        }

        return $this;
    }

    public function removeJobSearch(JobSearch $jobSearch): self
    {
        if ($this->jobSearches->removeElement($jobSearch)) {
            // set the owning side to null (unless already changed)
            if ($jobSearch->getCause() === $this) {
                $jobSearch->setCause(null);
            }
        }

        return $this;
    }

}
