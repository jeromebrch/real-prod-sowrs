<?php

namespace App\Entity;

use App\Repository\LevelStudyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LevelStudyRepository::class)
 */
class LevelStudy
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $wording;

    /**
     * @ORM\OneToMany(targetEntity=Candidate::class, mappedBy="levelStudy")
     */
    private $candidates;

    /**
     * @ORM\OneToMany(targetEntity=JobOffer::class, mappedBy="levelStudy")
     */
    private $jobOffers;

    public function __construct()
    {
        $this->candidates = new ArrayCollection();
        $this->jobOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(string $wording): self
    {
        $this->wording = $wording;

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
            $candidate->setLevelStudy($this);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        if ($this->candidates->removeElement($candidate)) {
            // set the owning side to null (unless already changed)
            if ($candidate->getLevelStudy() === $this) {
                $candidate->setLevelStudy(null);
            }
        }

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
            $jobOffer->setLevelStudy($this);
        }

        return $this;
    }

    public function removeJobOffer(JobOffer $jobOffer): self
    {
        if ($this->jobOffers->removeElement($jobOffer)) {
            // set the owning side to null (unless already changed)
            if ($jobOffer->getLevelStudy() === $this) {
                $jobOffer->setLevelStudy(null);
            }
        }

        return $this;
    }
}
