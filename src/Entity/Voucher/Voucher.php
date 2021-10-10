<?php

namespace App\Entity\Voucher;

use App\Entity\Order\Order;
use App\Model\CRUD\CRUDShowFieldsInterface;
use App\Repository\VoucherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoucherRepository::class)
 */
class Voucher implements CRUDShowFieldsInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fromPlace;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $toPlace;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="voucher")
     */
    private $orders;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $additional = [];

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFromPlace(): ?string
    {
        return $this->fromPlace;
    }

    public function setFromPlace(string $fromPlace): self
    {
        $this->fromPlace = $fromPlace;

        return $this;
    }

    public function getToPlace(): ?string
    {
        return $this->toPlace;
    }

    public function setToPlace(string $toPlace): self
    {
        $this->toPlace = $toPlace;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setVoucher($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getVoucher() === $this) {
                $order->setVoucher(null);
            }
        }

        return $this;
    }

    public function getAdditional(): ?array
    {
        return $this->additional;
    }

    public function setAdditional(?array $additional): self
    {
        $this->additional = $additional;

        return $this;
    }

    public function getTableFields(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'active?' => $this->getIsActive() ? 'true' : 'false',
            'price' => $this->getPrice(),
            'from' => $this->getFromPlace(),
            'to' => $this->getToPlace(),
        ];
    }
}
