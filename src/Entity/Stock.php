<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 3)]
    private string $size;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\ManyToOne(targetEntity: Sweatshirt::class, inversedBy: 'stocks')]
    private ?Sweatshirt $sweatshirt = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;
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

    public function getSweatshirt(): Sweatshirt
    {
        return $this->sweatshirt;
    }

    public function setSweatshirt(?Sweatshirt $sweatshirt): self
    {
        $this->sweatshirt = $sweatshirt;
        return $this;
    }
}
