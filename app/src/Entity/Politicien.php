<?php

namespace App\Entity;

use App\Repository\PoliticienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PoliticienRepository::class)]
class Politicien extends User
{
    #[ORM\ManyToOne(inversedBy: 'politiciens')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Parti $parti = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $biographie = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $photo = null;

    /**
     * @var Collection<int, Delit>
     */
    #[ORM\ManyToMany(targetEntity: Delit::class, inversedBy: 'politiciens')]
    private Collection $delits;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'auteur')]
    private Collection $commentaires;

    public function __construct()
    {
        parent::__construct();

        $this->delits = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getParti(): ?Parti
    {
        return $this->parti;
    }

    public function setParti(?Parti $parti): static
    {
        $this->parti = $parti;

        return $this;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(?string $biographie): static
    {
        $this->biographie = $biographie;
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Delit>
     */
    public function getDelits(): Collection
    {
        return $this->delits;
    }

    public function addDelit(Delit $delit): static
    {
        if (!$this->delits->contains($delit)) {
            $this->delits->add($delit);
            $delit->addPoliticien($this);
        }

        return $this;
    }

    public function removeDelit(Delit $delit): static
    {
        if ($this->delits->removeElement($delit)) {
            $delit->removePoliticien($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setAuteur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getAuteur() === $this) {
                $commentaire->setAuteur(null);
            }
        }

        return $this;
    }

    public function getNiveauCorruption(): string
    {
        $nombreDelits = $this->delits->count();

        if ($nombreDelits === 0) return "Saint 😇";
        if ($nombreDelits <= 2) return "Petit malin 😏";
        if ($nombreDelits <= 5) return "Corrompu moyen 😈";
        if ($nombreDelits <= 10) return "Grand escroc 🤑";

        return "Légende de la corruption 👑";
    }

    public function getRoles(): array
    {
        $roles = parent::getRoles();
        $roles[] = 'ROLE_POLITICIEN';

        return array_unique($roles);
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getMontantTotalCorruption(): int
    {
        $total = 0;
        foreach ($this->delits as $delit) {
            $total += $delit->getMontantTotalPaiements();
        }
        return $total;
    }

    public function getNombreCommentaires(): int
    {
        return $this->commentaires->count();
    }

    public function getDelitLePlusRecent(): ?Delit
    {
        if ($this->delits->isEmpty()) {
            return null;
        }

        $delits = $this->delits->toArray();
        usort($delits, fn(Delit $a, Delit $b) => $b->getDate() <=> $a->getDate());

        return $delits[0];
    }

    public function hasParti(): bool
    {
        return $this->parti !== null;
    }

    public function __toString(): string
    {
        $nom = $this->getFullName();

        return $nom;
    }
}
