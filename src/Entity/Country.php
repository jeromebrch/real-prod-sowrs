<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=JobSearch::class, mappedBy="country")
     */
    private $jobSearches;

    /**
     * @ORM\OneToMany(targetEntity=Candidate::class, mappedBy="authorizedCountry")
     */
    private $candidates;

    /**
     * @ORM\OneToMany(targetEntity=JobOffer::class, mappedBy="country")
     */
    private $jobOffers;


    public function __construct()
    {
        $this->jobSearches = new ArrayCollection();
        $this->candidates = new ArrayCollection();
        $this->jobOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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
            $jobSearch->setCountry($this);
        }

        return $this;
    }

    public function removeJobSearch(JobSearch $jobSearch): self
    {
        if ($this->jobSearches->removeElement($jobSearch)) {
            // set the owning side to null (unless already changed)
            if ($jobSearch->getCountry() === $this) {
                $jobSearch->setCountry(null);
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
            $candidate->setAuthorizedCountry($this);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        if ($this->candidates->removeElement($candidate)) {
            // set the owning side to null (unless already changed)
            if ($candidate->getAuthorizedCountry() === $this) {
                $candidate->setAuthorizedCountry(null);
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
            $jobOffer->setCountry($this);
        }

        return $this;
    }

    public function removeJobOffer(JobOffer $jobOffer): self
    {
        if ($this->jobOffers->removeElement($jobOffer)) {
            // set the owning side to null (unless already changed)
            if ($jobOffer->getCountry() === $this) {
                $jobOffer->setCountry(null);
            }
        }

        return $this;
    }

}
