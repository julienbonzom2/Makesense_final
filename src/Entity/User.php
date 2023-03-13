<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $avatar = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Nationality $nationality = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Decision::class)]
    private Collection $decisions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Associated::class)]
    private Collection $associateds;

    #[ORM\OneToMany(mappedBy: 'ongoingAuthor', targetEntity: OngoingDecision::class)]
    private Collection $ongoingDecisions;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    public function __construct()
    {
        $this->decisions = new ArrayCollection();
        $this->ongoingDecisions = new ArrayCollection();
        $this->associateds = new ArrayCollection();
        $this->setCreatedAt(new DateTimeImmutable());
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
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getNationality(): ?Nationality
    {
        return $this->nationality;
    }

    public function setNationality(?Nationality $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return Collection<int, Decision>
     */
    public function getDecisions(): Collection
    {
        return $this->decisions;
    }

    public function addDecision(Decision $decision): self
    {
        if (!$this->decisions->contains($decision)) {
            $this->decisions->add($decision);
            $decision->setAuthor($this);
        }

        return $this;
    }

    public function removeDecision(Decision $decision): self
    {
        if ($this->decisions->removeElement($decision)) {
            // set the owning side to null (unless already changed)
            if ($decision->getAuthor() === $this) {
                $decision->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OngoingDecision>
     */
    public function getOngoingDecisions(): Collection
    {
        return $this->ongoingDecisions;
    }

    public function addOngoingDecision(OngoingDecision $ongoingDecision): self
    {
        if (!$this->ongoingDecisions->contains($ongoingDecision)) {
            $this->ongoingDecisions->add($ongoingDecision);
            $ongoingDecision->setOngoingAuthor($this);
        }

        return $this;
    }

    public function removeOngoingDecision(OngoingDecision $ongoingDecision): self
    {
        if ($this->ongoingDecisions->removeElement($ongoingDecision)) {
            // set the owning side to null (unless already changed)
            if ($ongoingDecision->getOngoingAuthor() === $this) {
                $ongoingDecision->setOngoingAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Associated>
     */
    public function getAssociateds(): Collection
    {
        return $this->associateds;
    }

    public function addAssociated(Associated $associated): self
    {
        if (!$this->associateds->contains($associated)) {
            $this->associateds->add($associated);
            $associated->setUser($this);
        }

        return $this;
    }

    public function removeAssociated(Associated $associated): self
    {
        if ($this->associateds->removeElement($associated)) {
            // set the owning side to null (unless already changed)
            if ($associated->getUser() === $this) {
                $associated->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->firstname;
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
}
