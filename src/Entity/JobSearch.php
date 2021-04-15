<?php

namespace App\Entity;

use App\Repository\JobSearchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobSearchRepository::class)
 */
class JobSearch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $indemnity;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $jobTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Candidate::class, mappedBy="jobSearch")
     */
    private $candidates;

    /**
     * @ORM\ManyToOne(targetEntity=ContractType::class, inversedBy="jobSearches")
     */
    private $contractType;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="jobSearches")
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class)
     */
    private $department;

    /**
     * @ORM\ManyToOne(targetEntity=Cause::class, inversedBy="jobSearches")
     */
    private $cause;

    /**
     * @ORM\ManyToOne(targetEntity=Remuneration::class, inversedBy="jobSearches")
     */
    private $desiredRemuneration;

    /**
     * @ORM\ManyToOne (targetEntity=Region::class)
     */
    private $region;


    public function __construct()
    {
        $this->candidates = new ArrayCollection();
        $this->regions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndemnity(): ?string
    {
        return $this->indemnity;
    }

    public function setIndemnity(?string $indemnity): self
    {
        $this->indemnity = $indemnity;

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
     * @param $region
     */
    public function setRegion($region): self
    {
        $this->region = $region;
        return $this;
    }



    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags): void
    {
        $this->tags = $tags;
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
            $candidate->setJobSearch($this);
        }

        return $this;
    }

    public function removeCandidate(Candidate $candidate): self
    {
        if ($this->candidates->removeElement($candidate)) {
            // set the owning side to null (unless already changed)
            if ($candidate->getJobSearch() === $this) {
                $candidate->setJobSearch(null);
            }
        }

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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCause(): ?Cause
    {
        return $this->cause;
    }

    public function setCause(?Cause $cause): self
    {
        $this->cause = $cause;

        return $this;
    }

    public function getDesiredRemuneration(): ?Remuneration
    {
        return $this->desiredRemuneration;
    }

    public function setDesiredRemuneration(?Remuneration $desiredRemuneration): self
    {
        $this->desiredRemuneration = $desiredRemuneration;

        return $this;
    }



}
