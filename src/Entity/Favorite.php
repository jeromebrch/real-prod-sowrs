<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;

/**
 * @ORM\Entity(repositoryClass=FavoriteRepository::class)
 */
class Favorite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="favorites")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Cv::class, inversedBy="favorite")
     */
    private $cv;

    /**
     * @ORM\ManyToOne(targetEntity=JobOffer::class, inversedBy="favorite")
     */
    private $jobOffer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * @param mixed $cv
     */
    public function setCv($cv): self
    {
        $this->cv = $cv;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJobOffer()
    {
        return $this->jobOffer;
    }

    /**
     * @param mixed $jobOffer
     */
    public function setJobOffer($jobOffer): self
    {
        $this->jobOffer = $jobOffer;
        return $this;
    }

}
