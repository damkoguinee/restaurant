<?php

namespace App\Entity;

use App\Repository\MouvementProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MouvementProductRepository::class)]
class MouvementProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $origine = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ListeStock $stockProduct = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LieuxVentes $lieuVente = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $personnel = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementProducts')]
    private ?User $client = null;

    #[ORM\Column(nullable: true)]
    private ?float $quantite = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateOperation = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementProducts')]
    private ?ListeProductAchatFournisseur $achatFournisseur = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementProducts')]
    private ?CommandeProduct $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrigine(): ?string
    {
        return $this->origine;
    }

    public function setOrigine(string $origine): static
    {
        $this->origine = $origine;

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

    public function getStockProduct(): ?ListeStock
    {
        return $this->stockProduct;
    }

    public function setStockProduct(?ListeStock $stockProduct): static
    {
        $this->stockProduct = $stockProduct;

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

    public function getLieuVente(): ?LieuxVentes
    {
        return $this->lieuVente;
    }

    public function setLieuVente(?LieuxVentes $lieuVente): static
    {
        $this->lieuVente = $lieuVente;

        return $this;
    }

    public function getPersonnel(): ?User
    {
        return $this->personnel;
    }

    public function setPersonnel(?User $personnel): static
    {
        $this->personnel = $personnel;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
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

    public function getDateOperation(): ?\DateTimeInterface
    {
        return $this->dateOperation;
    }

    public function setDateOperation(\DateTimeInterface $dateOperation): static
    {
        $this->dateOperation = $dateOperation;

        return $this;
    }

    public function getAchatFournisseur(): ?ListeProductAchatFournisseur
    {
        return $this->achatFournisseur;
    }

    public function setAchatFournisseur(?ListeProductAchatFournisseur $achatFournisseur): static
    {
        $this->achatFournisseur = $achatFournisseur;

        return $this;
    }

    public function getCommande(): ?CommandeProduct
    {
        return $this->commande;
    }

    public function setCommande(?CommandeProduct $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
