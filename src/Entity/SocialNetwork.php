<?php

namespace App\Entity;

use App\Repository\SocialNetworkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SocialNetworkRepository::class)
 */
class SocialNetwork
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
    private $webSite;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $linkedin;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $stackOverflow;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $github;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $skype;

    /**
     * @ORM\OneToOne(targetEntity=Candidate::class, mappedBy="socialNetwork")
     */
    private $candidate;

    /**
     * @ORM\OneToOne(targetEntity=Recruiter::class, mappedBy="socialNetwork")
     */
    private $recruiter;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebSite(): ?string
    {
        return $this->webSite;
    }

    public function setWebSite(?string $webSite): self
    {
        $this->webSite = $webSite;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getStackOverflow(): ?string
    {
        return $this->stackOverflow;
    }

    public function setStackOverflow(?string $stackOverflow): self
    {
        $this->stackOverflow = $stackOverflow;

        return $this;
    }

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getSkype(): ?string
    {
        return $this->skype;
    }

    public function setSkype(?string $skype): self
    {
        $this->skype = $skype;

        return $this;
    }




}
