<?php

namespace App\Entity;

use App\Repository\WarehouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarehouseRepository::class)]
class Warehouse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'warehouses')]
    private Collection $users;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'warehouse_id')]
    private Collection $warehouse_transaction;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'warehouse')]
    private Collection $transactions;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->warehouse_transaction = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addWarehouse($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeWarehouse($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getWarehouseTransaction(): Collection
    {
        return $this->warehouse_transaction;
    }

    public function addWarehouseTransaction(Transaction $warehouseTransaction): static
    {
        if (!$this->warehouse_transaction->contains($warehouseTransaction)) {
            $this->warehouse_transaction->add($warehouseTransaction);
            $warehouseTransaction->setWarehouseId($this);
        }

        return $this;
    }

    public function removeWarehouseTransaction(Transaction $warehouseTransaction): static
    {
        if ($this->warehouse_transaction->removeElement($warehouseTransaction)) {
            // set the owning side to null (unless already changed)
            if ($warehouseTransaction->getWarehouseId() === $this) {
                $warehouseTransaction->setWarehouseId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setWarehouse($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getWarehouse() === $this) {
                $transaction->setWarehouse(null);
            }
        }

        return $this;
    }
}
