<?php

namespace App\Entity;

use App\Repository\BoissonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BoissonRepository::class)]
class Boisson extends Product
{
    #[ORM\Column(nullable: true)]
    private ?float $volume = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $unite = null;

    #[ORM\Column(length: 100)]
    private ?string $typeBoisson = null;

    #[ORM\ManyToOne(inversedBy: 'boissons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategorieBoisson $categorie = null;

    // #[ORM\OneToMany(targetEntity: CocktailRecette::class, mappedBy: 'boisson')]
    // private Collection $cocktailRecettes;

    // public function __construct()
    // {
    //     $this->cocktailRecettes = new ArrayCollection();
    // }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(?float $volume): static
    {
        $this->volume = $volume;
        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): static
    {
        $this->unite = $unite;
        return $this;
    }

    public function getTypeBoisson(): ?string
    {
        return $this->typeBoisson;
    }

    public function setTypeBoisson(string $typeBoisson): static
    {
        $this->typeBoisson = $typeBoisson;
        return $this;
    }

    public function getCategorie(): ?CategorieBoisson
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieBoisson $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    // /**
    //  * @return Collection<int, CocktailRecette>
    //  */
    // public function getCocktailRecettes(): Collection
    // {
    //     return $this->cocktailRecettes;
    // }

    // public function addCocktailRecette(CocktailRecette $cocktailRecette): static
    // {
    //     if (!$this->cocktailRecettes->contains($cocktailRecette)) {
    //         $this->cocktailRecettes->add($cocktailRecette);
    //         $cocktailRecette->setBoisson($this);
    //     }
    //     return $this;
    // }

    // public function removeCocktailRecette(CocktailRecette $cocktailRecette): static
    // {
    //     if ($this->cocktailRecettes->removeElement($cocktailRecette)) {
    //         if ($cocktailRecette->getBoisson() === $this) {
    //             $cocktailRecette->setBoisson(null);
    //         }
    //     }
    //     return $this;
    // }
}