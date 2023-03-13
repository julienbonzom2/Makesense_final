<?php

namespace App\Entity;

use App\Repository\OngoingAssociatedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OngoingAssociatedRepository::class)]
class OngoingAssociated
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ongoingStatus = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'ongoingAssociateds')]
    private ?OngoingDecision $ongoingDecision = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOngoingStatus(): ?string
    {
        return $this->ongoingStatus;
    }

    public function setOngoingStatus(string $ongoingStatus): self
    {
        $this->ongoingStatus = $ongoingStatus;

        return $this;
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

    public function getOngoingDecision(): ?OngoingDecision
    {
        return $this->ongoingDecision;
    }

    public function setOngoingDecision(?OngoingDecision $ongoingDecision): self
    {
        $this->ongoingDecision = $ongoingDecision;

        return $this;
    }

    public function __toString()
    {
        return $this->user->getFirstname() . ' ' . $this->user->getLastname();
    }
}
