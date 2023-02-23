<?php

namespace App\Entity;

use App\Repository\SauceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SauceRepository::class)]
class Sauce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'sauce', targetEntity: Tacos::class)]
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
            $taco->setSauce($this);
        }

        return $this;
    }

    public function removeTaco(Tacos $taco): self
    {
        if ($this->tacos->removeElement($taco)) {
            // set the owning side to null (unless already changed)
            if ($taco->getSauce() === $this) {
                $taco->setSauce(null);
            }
        }

        return $this;
    }
}
