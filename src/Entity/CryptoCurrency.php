<?php

namespace App\Entity;

use App\Repository\CryptoCurrencyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CryptoCurrencyRepository::class)]
class CryptoCurrency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $symbol = null;

    #[ORM\Column]
    private ?float $currentPrice = null;

    #[ORM\Column]
    private ?float $totalVolume = null;

    #[ORM\Column]
    private ?float $ath = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $athDate = null;

    #[ORM\Column]
    private ?float $atl = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $atlDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): static
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getCurrentPrice(): ?float
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(float $currentPrice): static
    {
        $this->currentPrice = $currentPrice;

        return $this;
    }

    public function getTotalVolume(): ?float
    {
        return $this->totalVolume;
    }

    public function setTotalVolume(float $totalVolume): static
    {
        $this->totalVolume = $totalVolume;

        return $this;
    }

    public function getAth(): ?float
    {
        return $this->ath;
    }

    public function setAth(float $ath): static
    {
        $this->ath = $ath;

        return $this;
    }

    public function getAthDate(): ?\DateTimeInterface
    {
        return $this->athDate;
    }

    public function setAthDate(\DateTimeInterface $athDate): static
    {
        $this->athDate = $athDate;

        return $this;
    }

    public function getAtl(): ?float
    {
        return $this->atl;
    }

    public function setAtl(float $atl): static
    {
        $this->atl = $atl;

        return $this;
    }

    public function getAtlDate(): ?\DateTimeInterface
    {
        return $this->atlDate;
    }

    public function setAtlDate(\DateTimeInterface $atlDate): static
    {
        $this->atlDate = $atlDate;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
