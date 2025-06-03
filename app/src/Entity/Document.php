<?php

namespace App\Entity;

use App\Enum\DocumentTypeEnum;
use App\Repository\DocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(enumType: DocumentTypeEnum::class)]
    private ?DocumentTypeEnum $type = null;

    #[ORM\Column(length: 500)]
    private ?string $chemin = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'documents')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Delit $delit = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Politicien $auteur = null;

    #[ORM\Column(nullable: true)]
    private ?int $tailleFichier = null;

    public function __construct()
    {
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

    public function getType(): ?DocumentTypeEnum
    {
        return $this->type;
    }

    public function setType(DocumentTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getChemin(): ?string
    {
        return $this->chemin;
    }

    public function setChemin(string $chemin): static
    {
        $this->chemin = $chemin;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getAuteur(): ?Politicien
    {
        return $this->auteur;
    }

    public function setAuteur(?Politicien $auteur): static
    {
        $this->auteur = $auteur;
        return $this;
    }

    public function getTailleFichier(): ?int
    {
        return $this->tailleFichier;
    }

    public function setTailleFichier(?int $tailleFichier): static
    {
        $this->tailleFichier = $tailleFichier;
        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->chemin ? pathinfo($this->chemin, PATHINFO_EXTENSION) : null;
    }

    public function getTailleFormatee(): ?string
    {
        if (!$this->tailleFichier) {
            return null;
        }

        $unites = ['B', 'KB', 'MB', 'GB'];
        $taille = $this->tailleFichier;
        $unite = 0;

        while ($taille >= 1024 && $unite < count($unites) - 1) {
            $taille /= 1024;
            $unite++;
        }

        return round($taille, 2) . ' ' . $unites[$unite];
    }
}
