<?php

namespace App\Entity;

use App\Repository\PharmacyInventoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PharmacyInventoryRepository::class)]
#[ORM\Table(name: 'pharmacy_inventory')]
class PharmacyInventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $pharmacy = null;

    #[ORM\ManyToOne(targetEntity: Medicine::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Medicine $medicine = null;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 0;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $purchasePrice = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $lastRestocked = null;

    public function __construct()
    {
        $this->lastRestocked = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPharmacy(): ?User
    {
        return $this->pharmacy;
    }

    public function setPharmacy(?User $pharmacy): self
    {
        $this->pharmacy = $pharmacy;
        return $this;
    }

    public function getMedicine(): ?Medicine
    {
        return $this->medicine;
    }

    public function setMedicine(?Medicine $medicine): self
    {
        $this->medicine = $medicine;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getPurchasePrice(): ?string
    {
        return $this->purchasePrice;
    }

    public function setPurchasePrice(string $purchasePrice): self
    {
        $this->purchasePrice = $purchasePrice;
        return $this;
    }

    public function getLastRestocked(): ?\DateTimeInterface
    {
        return $this->lastRestocked;
    }

    public function setLastRestocked(\DateTimeInterface $lastRestocked): self
    {
        $this->lastRestocked = $lastRestocked;
        return $this;
    }
}
