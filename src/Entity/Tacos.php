<?php

namespace App\Entity;

use App\Repository\TacosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacosRepository::class)]
class Tacos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tacos')]
    private ?Size $size = null;

    #[ORM\ManyToOne(inversedBy: 'tacos')]
    private ?Meat $meat = null;

    #[ORM\ManyToOne(inversedBy: 'tacos')]
    private ?Sauce $sauce = null;

    #[ORM\ManyToOne(inversedBy: 'tacos')]
    private ?Cheese $cheese = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSize(): ?Size
    {
        return $this->size;
    }

    public function setSize(?Size $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getMeat(): ?Meat
    {
        return $this->meat;
    }

    public function setMeat(?Meat $meat): self
    {
        $this->meat = $meat;

        return $this;
    }

    public function getSauce(): ?Sauce
    {
        return $this->sauce;
    }

    public function setSauce(?Sauce $sauce): self
    {
        $this->sauce = $sauce;

        return $this;
    }

    public function getCheese(): ?Cheese
    {
        return $this->cheese;
    }

    public function setCheese(?Cheese $cheese): self
    {
        $this->cheese = $cheese;

        return $this;
    }
}
