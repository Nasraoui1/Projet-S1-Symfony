<?php

namespace App\Entity;

use App\Repository\PartiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartiRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_PARTI_NOM', columns: ['nom'])]
class Parti
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 7, nullable: true)]
    private ?string $couleur = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $slogan = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Politicien>
     */
    #[ORM\OneToMany(targetEntity: Politicien::class, mappedBy: 'parti')]
    private Collection $politiciens;

    public function __construct()
    {
        $this->politiciens = new ArrayCollection();
        $this->dateCreation = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): static
    {
        if ($couleur && !preg_match('/^#[0-9A-Fa-f]{6}$/', $couleur)) {
            throw new \InvalidArgumentException('La couleur doit être au format hexadécimal (#RRGGBB).');
        }
        $this->couleur = $couleur;
        
        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(?string $slogan): static
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection<int, Politicien>
     */
    public function getPoliticiens(): Collection
    {
        return $this->politiciens;
    }

    public function addPoliticien(Politicien $politicien): static
    {
        if (!$this->politiciens->contains($politicien)) {
            $this->politiciens->add($politicien);
            $politicien->setParti($this);
        }

        return $this;
    }

    public function removePoliticien(Politicien $politicien): static
    {
        if ($this->politiciens->removeElement($politicien)) {
            // set the owning side to null (unless already changed)
            if ($politicien->getParti() === $this) {
                $politicien->setParti(null);
            }
        }

        return $this;
    }

    public function getNombrePoliticiens(): int
    {
        return $this->politiciens->count();
    }

    public function getNombreTotalDelits(): int
    {
        $total = 0;
        foreach ($this->politiciens as $politicien) {
            $total += $politicien->getDelits()->count();
        }

        return $total;
    }

    public function getPoliticiensLePlusCorrompu(): ?Politicien
    {
        $maxDelits = 0;
        $plusCorrompu = null;

        foreach ($this->politiciens as $politicien) {
            $nombreDelits = $politicien->getDelits()->count();
            if ($nombreDelits > $maxDelits) {
                $maxDelits = $nombreDelits;
                $plusCorrompu = $politicien;
            }
        }

        return $plusCorrompu;
    }

    public function __toString(): string
    {
        return $this->nom ?? '';
    }
}
