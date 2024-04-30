<?php

namespace App\Entity;

use App\Repository\ModificationCommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModificationCommandeRepository::class)]
class ModificationCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 13, scale: 2, nullable: true)]
    private ?string $prixVente = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCommande = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateSaisie = null;

    #[ORM\Column(length: 50)]
    private ?string $typeVente = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\Column(nullable: true, length: 100)]
    private ?string $typeProduct = null;

    #[ORM\ManyToOne(inversedBy: 'modificationCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $modifierPar = null;

    #[ORM\ManyToOne(inversedBy: 'modificationCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'modificationCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Table $emplacement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixVente(): ?string
    {
        return $this->prixVente;
    }

    public function setPrixVente(?string $prixVente): static
    {
        $this->prixVente = $prixVente;

        return $this;
    }


    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getDateSaisie(): ?\DateTimeInterface
    {
        return $this->dateSaisie;
    }

    public function setDateSaisie(\DateTimeInterface $dateSaisie): static
    {
        $this->dateSaisie = $dateSaisie;

        return $this;
    }

    public function getTypeVente(): ?string
    {
        return $this->typeVente;
    }

    public function setTypeVente(string $typeVente): static
    {
        $this->typeVente = $typeVente;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getTypeProduct(): ?string
    {
        return $this->typeProduct;
    }

    public function setTypeProduct(string $typeProduct): static
    {
        $this->typeProduct = $typeProduct;

        return $this;
    }

    public function getModifierPar(): ?User
    {
        return $this->modifierPar;
    }

    public function setModifierPar(?User $modifierPar): static
    {
        $this->modifierPar = $modifierPar;

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

    public function getEmplacement(): ?Table
    {
        return $this->emplacement;
    }

    public function setEmplacement(?Table $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }
}
