<?php

namespace App\Entity;

use App\Enum\PaiementTypeEnum;
use App\Repository\PaiementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
#[ORM\Index(columns: ['date'], name: 'idx_paiement_date')]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?int $montant = null;

    #[ORM\Column(length: 3)]
    private ?string $devise = 'EUR';

    #[ORM\Column(enumType: PaiementTypeEnum::class)]
    private ?PaiementTypeEnum $type = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Delit $delit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        if ($montant < 0) {
            throw new \InvalidArgumentException('Le montant ne peut pas être négatif.');
        }
        $this->montant = $montant;

        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(string $devise): static
    {
        $this->devise = strtoupper($devise);

        return $this;
    }

    public function getType(): ?PaiementTypeEnum
    {
        return $this->type;
    }

    public function setType(PaiementTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

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

    public function getMontantFormate(): string
    {
        return number_format($this->montant / 100, 2, ',', ' ') . ' ' . $this->devise;
    }

    public function getMontantEnEuros(): float
    {
        return $this->montant / 100;
    }
}
