<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uid = null;

    #[ORM\Column]
    private ?int $totalAmount = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column]
    private array $paymentsDue = [];

    #[ORM\Column]
    private ?\DateTime $lastSettlementDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUid(): ?string
    {
        return $this->uid;
    }

    public function setUid(string $uid): static
    {
        $this->uid = $uid;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getPaymentsDue(): array
    {
        return $this->paymentsDue;
    }

    public function setPaymentsDue(array $paymentsDue): static
    {
        $this->paymentsDue = $paymentsDue;

        return $this;
    }

    public function getLastSettlementDate(): ?\DateTime
    {
        return $this->lastSettlementDate;
    }

    public function setLastSettlementDate(\DateTime $lastSettlementDate): static
    {
        $this->lastSettlementDate = $lastSettlementDate;

        return $this;
    }
}
