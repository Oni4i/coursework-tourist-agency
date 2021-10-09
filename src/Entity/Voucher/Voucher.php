<?php

namespace App\Entity\Voucher;

use App\Repository\VoucherRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VoucherRepository::class)
 */
class Voucher
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
}
