<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 *
 * Class Item
 * @package App\Entity
 */
class Item
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
    private $name;

    /**
     *  @ORM\Column(type="float")
     */
    private $priceCurrency;

    /**
     * @ORM\Column(type="float")
     */
    private $priceAmount;

    /**
     * @ORM\ManyToOne(targetEntity=Seller::class, inversedBy="items", fetch="EAGER")
     */
    private $sellerReference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPriceCurrency(): ?string
    {
        return $this->priceCurrency;
    }

    public function setPriceCurrency(string $priceCurrency): self
    {
        $this->priceCurrency = $priceCurrency;

        return $this;
    }

    public function getPriceAmount(): ?float
    {
        return $this->priceAmount;
    }

    public function setPriceAmount(float $priceAmount): self
    {
        $this->priceAmount = $priceAmount;

        return $this;
    }

    public function getSellerReference(): ?Seller
    {
        return $this->sellerReference;
    }

    public function setSellerReference(?Seller $sellerReference): self
    {
        $this->sellerReference = $sellerReference;

        return $this;
    }
}
