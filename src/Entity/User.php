<?php
namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="typeUser", type="string")
 * @ORM\DiscriminatorMap({"candidate"="Candidate", "recruiter"="Recruiter", "user"="User"})
 * @UniqueEntity(fields={"email"}, message="Il existe déjà un compte avec cet e-mail.")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_add_action_recruiter"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Veuillez renseigner ce champ !")
     * @Assert\Email()
     */
    protected $email;


    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min="6", minMessage="6 caractères minimum !")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Veuillez renseignez ce champ !")
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Veuillez renseignez ce champ !")
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Veuillez renseignez ce champ !")
     * @Assert\Length(max="15", maxMessage="Numéro de téléphone invalide")
     */
    protected $phone;

    /**
     * @ORM\Column(type="date")
     */
    protected $registrationDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $about;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isVerified = false;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $private;


    /**
     * @ORM\OneToOne(targetEntity=Scoring::class, inversedBy="user", cascade={"persist", "remove"})
     */
    protected $scoring;

    /**
     * @ORM\OneToOne(targetEntity=Picture::class)
     * @ORM\JoinColumn(nullable=true)
     */
    protected $picture;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="writerUser", cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="userRecipient", cascade="remove")
     */
    private $receivedMessages;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="userSender", cascade="remove")
     */
    private $sendedMessages;



    public function __construct()
    {
        $this->registrationDate = new DateTime;
        $this->private = false;
        $this->updatedAt = new DateTime();
        $this->comments = new ArrayCollection();
        $this->messages = new ArrayCollection();

    }

    /**
     * @return mixed
     */
    public function getReceivedMessages()
    {
        return $this->receivedMessages;
    }

    /**
     * @param mixed $receivedMessages
     */
    public function setReceivedMessages($receivedMessages): self
    {
        $this->receivedMessages = $receivedMessages;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSendedMessages()
    {
        return $this->sendedMessages;
    }

    /**
     * @param mixed $sendedMessages
     */
    public function setSendedMessages($sendedMessages): self
    {
        $this->sendedMessages = $sendedMessages;
        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive Data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getRegistrationDate(): ?DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }


    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): self
    {
        $this->about = $about;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): self
    {
        $this->private = $private;

        return $this;
    }


    public function getScoring(): ?scoring
    {
        return $this->scoring;
    }

    public function setScoring(?scoring $scoring): self
    {
        $this->scoring = $scoring;

        return $this;
    }

    public function getPicture(): ?Picture
    {
        return $this->picture;
    }

    public function setPicture(?Picture $picture): self
    {
        /* unset the owning side of the relation if necessary
        if ($picture === null && $this->picture !== null) {
            $this->picture->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($picture !== null && $picture->getUser() !== $this) {
            $picture->setUser($this);
        }*/

        $this->picture = $picture;

        return $this;
    }

    public function serialize() {
        return serialize(array($this->id, $this->email, $this->password));
    }
    public function unserialize($serialized) {
        list ($this->id, $this->email, $this->password) = unserialize($serialized, array('allowed_classes' => false));
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setWriterUser($this);
        } return $this;
    }


    public function addMessages(Message $message): self
    {
        if (!$this->sendedMessages->contains($message)) {
            $this->sendedMessages[] = $message;
            $message->setUserRecipient($this);
            $message->setUserSender($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getWriterUser() === $this) {
                $comment->setWriterUser(null);
            }
        }
        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->receivedMessages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUserRecipient() === $this) {
                $message->setUserRecipient(null);
            }
            // set the owning side to null (unless already changed)
            if ($message->getUserSender() === $this) {
                $message->setUserSender(null);
            }
        }

        return $this;
    }

}

