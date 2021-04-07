<?php

namespace App\Entity;

use App\Repository\ScoringRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScoringRepository::class)
 */
class Scoring
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $social;

    /**
     * @ORM\Column(type="integer")
     */
    private $environment;

    /**
     * @ORM\Column(type="integer")
     */
    private $economy;

    /**
     * @ORM\Column(type="integer")
     */
    private $societal;

    /**
     * @ORM\Column(type="array")
     */
    private $choices = [];

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $greatestValue;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="scoring", cascade={"persist", "remove"})
     */
    private $user;


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
        // unset the owning side of the relation if necessary
        if ($user === null && $this->y !== null) {
            $this->y->setScoring(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getScoring() !== $this) {
            $user->setScoring($this);
        }

        $this->user = $user;

        return $this;
    }

    public function getSocial(): ?int
    {
        return $this->social;
    }

    public function setSocial(int $social): self
    {
        $this->social = $social;

        return $this;
    }

    public function getEnvironment(): ?int
    {
        return $this->environment;
    }

    public function setEnvironment(int $environment): self
    {
        $this->environment = $environment;

        return $this;
    }

    public function getEconomy(): ?int
    {
        return $this->economy;
    }

    public function setEconomy(int $economy): self
    {
        $this->economy = $economy;

        return $this;
    }

    public function getSocietal(): ?int
    {
        return $this->societal;
    }

    public function setSocietal(int $societal): self
    {
        $this->societal = $societal;

        return $this;
    }

    public function getChoices(): ?array
    {
        return $this->choices;
    }

    public function setChoices(array $choices): self
    {
        $this->choices = $choices;

        return $this;
    }



    public function setGreatestValue(string $greatestValue): self
    {
        $this->greatestValue = $greatestValue;

        return $this;
    }

    public function getGreatestValue(array $resultat)
    {
        $bigValue = '';

        $social = $resultat['score'] [0];
        $environment = $resultat['score'] [1];
        $economy = $resultat['score'] [2];
        $societal = $resultat['score'] [3];

        $maxValue = max($resultat['score']);

        switch ($maxValue){
            case $social:
                $bigValue = 'social';
                break;
            case $environment:
                $bigValue = 'environnemental';
                break;
            case $economy:
                $bigValue = 'economique';
                break;
            case $societal:
                $bigValue = 'societal';
                break;
        }
        return $bigValue;
    }

    public function getDominantSens(){
        return $this->greatestValue;
    }
    public function getDominantPourcent(){
        $pourcent = 0;
        switch ($this->greatestValue){
            case 'social':
                $pourcent = $this->social;
                break;
            case 'economique':
                $pourcent = $this->economy;
                break;
            case 'societal':
                $pourcent = $this->societal;
                break;
            case 'environnemental':
                $pourcent = $this->environment;
                break;
        }
        return $pourcent;
    }

}
