<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient extends Product
{

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $uniteMesure = null;   

    public function getUniteMesure(): ?string
    {
        return $this->uniteMesure;
    }

    public function setUniteMesure(?string $uniteMesure): static
    {
        $this->uniteMesure = $uniteMesure;

        return $this;
    }
}
