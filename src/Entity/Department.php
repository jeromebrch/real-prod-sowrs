<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
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
}
