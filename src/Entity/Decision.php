<?php

namespace App\Entity;

use App\DataFixtures\CategoryFixtures;
use App\Repository\DecisionRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DecisionRepository::class)]
class Decision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'The title cannot be blank')]
    #[Assert\Length(
        max: 255,
        maxMessage: "The title cannot be longer than 255 characters"
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'The problem cannot be blank')]
    private ?string $problem = null;

    #[ORM\Column(length: 50)]
    private ?string $impactRate = null;

    #[ORM\Column(length: 50)]
    private ?string $effortRate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $firstDecision = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $finalDecision = null;

    #[ORM\ManyToOne(inversedBy: 'decisions')]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'decisions')]
    #[Assert\NotBlank(message: 'You have to choose a category')]
    //#[Assert\Choice([CategoryFixtures::CATEGORIES], message: 'Please choose a valid category')]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'decisions')]
    private ?Status $status = null;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Comments::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'decision', targetEntity: Associated::class, cascade: ['remove'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'cascade')]
    private Collection $associateds;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $profit = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $drawback = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $firstDecisionAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $finalDecisionAt = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->associateds = new ArrayCollection();
        $this->setCreatedAt(new DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProblem(): ?string
    {
        return $this->problem;
    }

    public function setProblem(string $problem): self
    {
        $this->problem = $problem;

        return $this;
    }

    public function getImpactRate(): ?string
    {
        return $this->impactRate;
    }

    public function setImpactRate(string $impactRate): self
    {
        $this->impactRate = $impactRate;

        return $this;
    }

    public function getEffortRate(): ?string
    {
        return $this->effortRate;
    }

    public function setEffortRate(string $effortRate): self
    {
        $this->effortRate = $effortRate;

        return $this;
    }

    public function getFirstDecision(): ?string
    {
        return $this->firstDecision;
    }

    public function setFirstDecision(?string $firstDecision): self
    {
        $this->firstDecision = $firstDecision;

        return $this;
    }

    public function getFinalDecision(): ?string
    {
        return $this->finalDecision;
    }

    public function setFinalDecision(?string $finalDecision): self
    {
        $this->finalDecision = $finalDecision;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setDecision($this);
        }

        return $this;
    }

//    public function removeComment(Comments $comment): self
//    {
//        if ($this->comments->removeElement($comment)) {
//            // set the owning side to null (unless already changed)
//            if ($comment->getDecision() === $this) {
//                $comment->setDecision(null);
//            }
//        }
//
//        return $this;
//    }

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
            $associated->setDecision($this);
        }

        return $this;
    }

//    public function removeAssociated(Associated $associated): self
//    {
//        if ($this->associateds->removeElement($associated)) {
//            // set the owning side to null (unless already changed)
//            if ($associated->getDecision() === $this) {
//                $associated->setDecision(null);
//            }
//        }
//
//        return $this;
//    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getProfit(): ?string
    {
        return $this->profit;
    }

    public function setProfit(string $profit): self
    {
        $this->profit = $profit;

        return $this;
    }

    public function getDrawback(): ?string
    {
        return $this->drawback;
    }

    public function setDrawback(string $drawback): self
    {
        $this->drawback = $drawback;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getFirstDecisionAt(): ?\DateTimeImmutable
    {
        return $this->firstDecisionAt;
    }

    public function setFirstDecisionAt(?\DateTimeImmutable $firstDecisionAt): self
    {
        $this->firstDecisionAt = $firstDecisionAt;

        return $this;
    }

    public function getFinalDecisionAt(): ?\DateTimeImmutable
    {
        return $this->finalDecisionAt;
    }

    public function setFinalDecisionAt(?\DateTimeImmutable $finalDecisionAt): self
    {
        $this->finalDecisionAt = $finalDecisionAt;

        return $this;
    }

    public function getInFavorVotes(): int
    {
        $associateds = $this->getAssociateds();
        $inFavor = 0;
        foreach ($associateds as $associated) {
            if (!is_null($associated->isIsFavorable())) {
                if ($associated->isIsFavorable()) {
                    $inFavor++;
                }
            }
        }
        return $inFavor;
    }

    public function getNotInFavorVotes(): int
    {
        $associateds = $this->getAssociateds();
        $notInFavor = 0;
        foreach ($associateds as $associated) {
            if (!is_null($associated->isIsFavorable())) {
                if (!$associated->isIsFavorable()) {
                    $notInFavor++;
                }
            }
        }
        return $notInFavor;
    }
}
