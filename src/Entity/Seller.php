<?php

namespace App\Entity;

use App\Repository\SellerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SellerRepository::class)
 */
class Seller
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
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="sellerReference")
     */
    private $items;

    /**
     * @ORM\OneToOne(targetEntity=Payout::class, mappedBy="sellerReference", cascade={"persist", "remove"})
     */
    private $payout;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setSellerReference($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getSellerReference() === $this) {
                $item->setSellerReference(null);
            }
        }

        return $this;
    }

    public function getPayout(): ?Payout
    {
        return $this->payout;
    }

    public function setPayout(?Payout $payout): self
    {
        $this->payout = $payout;

        // set (or unset) the owning side of the relation if necessary
        $newSellerReference = null === $payout ? null : $this;
        if ($payout->getSellerReference() !== $newSellerReference) {
            $payout->setSellerReference($newSellerReference);
        }

        return $this;
    }
}
