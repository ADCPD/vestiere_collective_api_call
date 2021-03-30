<?php

namespace App\Entity;

use App\Repository\PayoutRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=PayoutRepository::class)
 */
class Payout
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\LessThan(1000000)
     */
    private $amount;

    /**
     * @ORM\Column(type="float")
     */
    private $currency;

    /**
     * @ORM\OneToOne(targetEntity=Seller::class, inversedBy="payout", cascade={"persist", "remove"})
     */
    private $sellerReference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): ?float
    {
        return $this->currency;
    }

    public function setCurrency(float $currency): self
    {
        $this->currency = $currency;

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
