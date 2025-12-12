<?php

namespace App\Entity;

use App\Repository\SupplierInventoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplierInventoryRepository::class)]
#[ORM\Table(name: 'supplier_inventory')]
class SupplierInventory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Supplier::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Supplier $supplier = null;

    #[ORM\ManyToOne(targetEntity: Medicine::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Medicine $medicine = null;

    #[ORM\Column(type: 'integer')]
    private int $quantity = 0;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private ?string $wholesalePrice = null;

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

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): self
    {
        $this->supplier = $supplier;
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

    public function getWholesalePrice(): ?string
    {
        return $this->wholesalePrice;
    }

    public function setWholesalePrice(string $wholesalePrice): self
    {
        $this->wholesalePrice = $wholesalePrice;
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
