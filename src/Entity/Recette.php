<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]

// #[ORM\InheritanceType("JOINED")]
// #[ORM\DiscriminatorColumn(name:"type", type:"string")]
// #[ORM\DiscriminatorMap([
//     "platRecette" => PlatRecette::class,
//     'chichaRecette' => ChichaRecette::class,
//     'cocktailRecette' => CocktailRecette::class,
// ])]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $quantite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'recettesIngredient')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $ingredient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(?float $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getIngredient(): ?Product
    {
        return $this->ingredient;
    }

    public function setIngredient(?Product $ingredient): static
    {
        $this->ingredient = $ingredient;

        return $this;
    }
}
