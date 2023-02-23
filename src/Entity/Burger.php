<?php

namespace App\Entity;

use App\Repository\BurgerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
class Burger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    #[ORM\ManyToOne(inversedBy: 'burgers')]
    private ?Frie $frie = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $duration = null;

    #[ORM\Column(nullable: true)]
    private ?int $durationS = null;

    #[ORM\Column(nullable: true)]
    private ?bool $frites = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePath = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getFrie(): ?Frie
    {
        return $this->frie;
    }

    public function setFrie(?Frie $frie): self
    {
        $this->frie = $frie;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(?\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDurationS(): ?int
    {
        return $this->durationS;
    }

    public function setDurationS(?int $durationS): self
    {
        $this->durationS = $durationS;

        return $this;
    }

    public function __toString(): string
    {
       return $this->getFrie();
    }

    public function isFrites(): ?bool
    {
        return $this->frites;
    }

    public function setFrites(?bool $frites): self
    {
        $this->frites = $frites;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }


    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }


}
