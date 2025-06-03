<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateModification = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Delit $delit = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'reponses')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?self $commentaireParent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'commentaireParent')]
    private Collection $reponses;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Politicien $auteur = null;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
        $this->dateCreation = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateModification(): ?\DateTimeImmutable
    {
        return $this->dateModification;
    }

    public function setDateModification(?\DateTimeImmutable $dateModification): static
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    public function getDelit(): ?Delit
    {
        return $this->delit;
    }

    public function setDelit(?Delit $delit): static
    {
        $this->delit = $delit;

        return $this;
    }

    public function getCommentaireParent(): ?self
    {
        return $this->commentaireParent;
    }

    public function setCommentaireParent(?self $commentaireParent): static
    {
        if ($commentaireParent !== null && $commentaireParent->getCommentaireParent() !== null) {
            throw new \InvalidArgumentException('Impossible de répondre à une réponse. Vous ne pouvez répondre qu\'aux commentaires principaux.');
        }

        $this->commentaireParent = $commentaireParent;
        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(self $reponse): static
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses->add($reponse);
            $reponse->setCommentaireParent($this);
        }

        return $this;
    }

    public function removeReponse(self $reponse): static
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getCommentaireParent() === $this) {
                $reponse->setCommentaireParent(null);
            }
        }

        return $this;
    }

    public function getAuteur(): ?Politicien
    {
        return $this->auteur;
    }

    public function setAuteur(?Politicien $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function isCommentairePrincipal(): bool
    {
        return $this->commentaireParent === null;
    }

    public function isReponse(): bool
    {
        return $this->commentaireParent !== null;
    }

    public function getNombreReponses(): int
    {
        return $this->reponses->count();
    }
}
