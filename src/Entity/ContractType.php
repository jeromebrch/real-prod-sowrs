<?php

namespace App\Entity;

use App\Repository\ContractTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContractTypeRepository::class)
 */
class ContractType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $wording;

    /**
     * @ORM\OneToMany(targetEntity=JobSearch::class, mappedBy="contractType")
     */
    private $jobSearches;

    /**
     * @ORM\OneToMany(targetEntity=JobOffer::class, mappedBy="contractType")
     */
    private $jobOffers;

    public function __construct()
    {
        $this->jobSearches = new ArrayCollection();
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
            $jobSearch->setContractType($this);
        }

        return $this;
    }

    public function removeJobSearch(JobSearch $jobSearch): self
    {
        if ($this->jobSearches->removeElement($jobSearch)) {
            // set the owning side to null (unless already changed)
            if ($jobSearch->getContractType() === $this) {
                $jobSearch->setContractType(null);
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
            $jobOffer->setContractType($this);
        }

        return $this;
    }

    public function removeJobOffer(JobOffer $jobOffer): self
    {
        if ($this->jobOffers->removeElement($jobOffer)) {
            // set the owning side to null (unless already changed)
            if ($jobOffer->getContractType() === $this) {
                $jobOffer->setContractType(null);
            }
        }

        return $this;
    }
}
