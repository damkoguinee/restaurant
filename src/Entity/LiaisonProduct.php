<?php

namespace App\Entity;

use App\Repository\LiaisonProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: LiaisonProductRepository::class)]
#[UniqueEntity(fields: ['product2'], message: 'Ce produit a déjà été lié avec un autre.')]
class LiaisonProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'liaisonProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product1 = null;

    #[ORM\ManyToOne(inversedBy: 'liaisonProducts2')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct1(): ?Product
    {
        return $this->product1;
    }

    public function setProduct1(?Product $product1): static
    {
        $this->product1 = $product1;

        return $this;
    }

    public function getProduct2(): ?Product
    {
        return $this->product2;
    }

    public function setProduct2(?Product $product2): static
    {
        $this->product2 = $product2;

        return $this;
    }

    
}
