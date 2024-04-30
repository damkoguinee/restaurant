<?php

namespace App\Entity;

use App\Repository\TypeCocktailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeCocktailRepository::class)]
class TypeCocktail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(targetEntity: Cocktail::class, mappedBy: 'typeCocktail')]
    private Collection $cocktails;

    public function __construct()
    {
        $this->cocktails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Cocktail>
     */
    public function getCocktails(): Collection
    {
        return $this->cocktails;
    }

    public function addCocktail(Cocktail $cocktail): static
    {
        if (!$this->cocktails->contains($cocktail)) {
            $this->cocktails->add($cocktail);
            $cocktail->setTypeCocktail($this);
        }

        return $this;
    }

    public function removeCocktail(Cocktail $cocktail): static
    {
        if ($this->cocktails->removeElement($cocktail)) {
            // set the owning side to null (unless already changed)
            if ($cocktail->getTypeCocktail() === $this) {
                $cocktail->setTypeCocktail(null);
            }
        }

        return $this;
    }
}
