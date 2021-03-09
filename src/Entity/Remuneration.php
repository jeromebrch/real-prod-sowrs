<?php

namespace App\Entity;

use App\Repository\RemunerationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RemunerationRepository::class)
 */
class Remuneration
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
    private $text;

    /**
     * @ORM\OneToMany(targetEntity=JobSearch::class, mappedBy="desiredRemuneration")
     */
    private $jobSearches;



    public function __construct()
    {
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

    public function setText(string $text): self
    {
        $this->text = $text;

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
            $jobSearch->setDesiredRemuneration($this);
        }

        return $this;
    }

    public function removeJobSearch(JobSearch $jobSearch): self
    {
        if ($this->jobSearches->removeElement($jobSearch)) {
            // set the owning side to null (unless already changed)
            if ($jobSearch->getDesiredRemuneration() === $this) {
                $jobSearch->setDesiredRemuneration(null);
            }
        }

        return $this;
    }
}
