<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $unit = null;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'product')]
    private Collection $warehouse;

    public function __construct()
    {
        $this->warehouse = new ArrayCollection();
    }

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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getWarehouse(): Collection
    {
        return $this->warehouse;
    }

    public function addWarehouse(Transaction $warehouse): static
    {
        if (!$this->warehouse->contains($warehouse)) {
            $this->warehouse->add($warehouse);
            $warehouse->setProduct($this);
        }

        return $this;
    }

    public function removeWarehouse(Transaction $warehouse): static
    {
        if ($this->warehouse->removeElement($warehouse)) {
            // set the owning side to null (unless already changed)
            if ($warehouse->getProduct() === $this) {
                $warehouse->setProduct(null);
            }
        }

        return $this;
    }
}
