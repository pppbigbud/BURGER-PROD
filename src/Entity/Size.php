<?php

namespace App\Entity;

use App\Repository\SizeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SizeRepository::class)]
class Size
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[ORM\OneToMany(mappedBy: 'size', targetEntity: Tacos::class)]
    private Collection $tacos;

    public function __construct()
    {
        $this->tacos = new ArrayCollection();
    }

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Tacos>
     */
    public function getTacos(): Collection
    {
        return $this->tacos;
    }

    public function addTaco(Tacos $taco): self
    {
        if (!$this->tacos->contains($taco)) {
            $this->tacos->add($taco);
            $taco->setSize($this);
        }

        return $this;
    }

    public function removeTaco(Tacos $taco): self
    {
        if ($this->tacos->removeElement($taco)) {
            // set the owning side to null (unless already changed)
            if ($taco->getSize() === $this) {
                $taco->setSize(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }


}
