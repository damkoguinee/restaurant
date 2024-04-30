<?php

namespace App\Entity;

use App\Repository\CocktailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CocktailRepository::class)]
class Cocktail extends Product
{
    

    #[ORM\Column(length: 6)]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'cocktails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeCocktail $typeCocktail = null;

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTypeCocktail(): ?TypeCocktail
    {
        return $this->typeCocktail;
    }

    public function setTypeCocktail(?TypeCocktail $typeCocktail): static
    {
        $this->typeCocktail = $typeCocktail;

        return $this;
    }

    
    
}
