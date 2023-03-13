<?php

namespace App\Entity;

use App\DataFixtures\CategoryFixtures;
use App\Repository\OngoingDecisionRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OngoingDecisionRepository::class)]
class OngoingDecision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ongoingTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ongoingDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ongoingProblem = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $ongoingImpactRate = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $ongoingEffortRate = null;

    #[ORM\ManyToOne(inversedBy: 'ongoingDecisions')]
    private ?User $ongoingAuthor = null;

    #[ORM\OneToMany(mappedBy: 'ongoingDecision', targetEntity: OngoingAssociated::class)]
    private Collection $ongoingAssociateds;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ongoingProfit = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ongoingDrawback = null;

    #[ORM\ManyToOne]
    private ?Category $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $currentStep = null;

    public function __construct()
    {
        $this->ongoingAssociateds = new ArrayCollection();
        $this->setCreatedAt(new DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOngoingTitle(): ?string
    {
        return $this->ongoingTitle;
    }

    public function setOngoingTitle(?string $ongoingTitle): self
    {
        $this->ongoingTitle = $ongoingTitle;

        return $this;
    }

    public function getOngoingDescription(): ?string
    {
        return $this->ongoingDescription;
    }

    public function setOngoingDescription(?string $ongoingDescription): self
    {
        $this->ongoingDescription = $ongoingDescription;

        return $this;
    }

    public function getOngoingProblem(): ?string
    {
        return $this->ongoingProblem;
    }

    public function setOngoingProblem(?string $ongoingProblem): self
    {
        $this->ongoingProblem = $ongoingProblem;

        return $this;
    }

    public function getOngoingImpactRate(): ?string
    {
        return $this->ongoingImpactRate;
    }

    public function setOngoingImpactRate(?string $ongoingImpactRate): self
    {
        $this->ongoingImpactRate = $ongoingImpactRate;

        return $this;
    }

    public function getOngoingEffortRate(): ?string
    {
        return $this->ongoingEffortRate;
    }

    public function setOngoingEffortRate(?string $ongoingEffortRate): self
    {
        $this->ongoingEffortRate = $ongoingEffortRate;

        return $this;
    }

    public function getOngoingAuthor(): ?User
    {
        return $this->ongoingAuthor;
    }

    public function setOngoingAuthor(?User $ongoingAuthor): self
    {
        $this->ongoingAuthor = $ongoingAuthor;

        return $this;
    }

    /**
     * @return Collection<int, OngoingAssociated>
     */
    public function getOngoingAssociateds(): Collection
    {
        return $this->ongoingAssociateds;
    }

    public function addOngoingAssociated(OngoingAssociated $ongoingAssociated): self
    {
        if (!$this->ongoingAssociateds->contains($ongoingAssociated)) {
            $this->ongoingAssociateds->add($ongoingAssociated);
            $ongoingAssociated->setOngoingDecision($this);
        }

        return $this;
    }

    public function removeOngoingAssociated(OngoingAssociated $ongoingAssociated): self
    {
        if ($this->ongoingAssociateds->removeElement($ongoingAssociated)) {
            // set the owning side to null (unless already changed)
            if ($ongoingAssociated->getOngoingDecision() === $this) {
                $ongoingAssociated->setOngoingDecision(null);
            }
        }

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getOngoingProfit(): ?string
    {
        return $this->ongoingProfit;
    }

    public function setOngoingProfit(?string $ongoingProfit): self
    {
        $this->ongoingProfit = $ongoingProfit;

        return $this;
    }

    public function getOngoingDrawback(): ?string
    {
        return $this->ongoingDrawback;
    }

    public function setOngoingDrawback(?string $ongoingDrawback): self
    {
        $this->ongoingDrawback = $ongoingDrawback;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCurrentStep(): ?string
    {
        return $this->currentStep;
    }

    public function setCurrentStep(?string $currentStep): self
    {
        $this->currentStep = $currentStep;

        return $this;
    }
}
