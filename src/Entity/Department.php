<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $cp;

    /**
     * @ORM\OneToMany (targetEntity=Candidate::class, mappedBy="department")
     */
    private $candidate;

    /**
     * @ORM\OneToMany (targetEntity=Recruiter::class, mappedBy="department")
     */
    private $recruiter;

    /**
     * @ORM\OneToMany(targetEntity=JobSearch::class, mappedBy="department")
     */
    private $jobSearches;

    public function __construct()
    {
        $this->jobSearches = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getRecruiter()
    {
        return $this->recruiter;
    }

    /**
     * @param mixed $recruiter
     */
    public function setRecruiter($recruiter): self
    {
        $this->recruiter = $recruiter;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getCandidate()
    {
        return $this->candidate;
    }

    /**
     * @param mixed $candidate
     */
    public function setCandidate($candidate): self
    {
        $this->candidate = $candidate;
        return  $this;
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

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(int $cp): self
    {
        $this->cp = $cp;

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
            $jobSearch->setDepartment($this);
        }

        return $this;
    }

    public function removeJobSearch(JobSearch $jobSearch): self
    {
        if ($this->jobSearches->removeElement($jobSearch)) {
            // set the owning side to null (unless already changed)
            if ($jobSearch->getDepartment() === $this) {
                $jobSearch->setDepartment(null);
            }
        }

        return $this;
    }
}
