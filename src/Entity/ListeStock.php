<?php

namespace App\Entity;

use App\Repository\ListeStockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListeStockRepository::class)]
class ListeStock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'listeStocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LieuxVentes $lieuVente = null;

    #[ORM\ManyToOne(inversedBy: 'listeStocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $responsable = null;

    #[ORM\Column(length: 100)]
    private ?string $nomStock = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(nullable: true)]
    private ?float $surface = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbrePieces = null;

    #[ORM\Column(length: 15)]
    private ?string $type = null;

    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'emplacement', orphanRemoval: true, cascade: ['persist'])]
    private Collection $stocks;

    #[ORM\OneToMany(targetEntity: ListeProductAchatFournisseur::class, mappedBy: 'stock')]
    private Collection $listeProductAchatFournisseurs;

    #[ORM\OneToMany(targetEntity: MouvementProduct::class, mappedBy: 'stockProduct')]
    private Collection $mouvementProducts;

    public function __construct()
    {
        $this->stocks = new ArrayCollection();
        $this->listeProductAchatFournisseurs = new ArrayCollection();
        $this->mouvementProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getResponsable(): ?User
    {
        return $this->responsable;
    }

    public function setResponsable(?User $responsable): static
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getNomStock(): ?string
    {
        return $this->nomStock;
    }

    public function setNomStock(string $nomStock): static
    {
        $this->nomStock = $nomStock;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(?float $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function getNbrePieces(): ?int
    {
        return $this->nbrePieces;
    }

    public function setNbrePieces(?int $nbrePieces): static
    {
        $this->nbrePieces = $nbrePieces;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setEmplacement($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getEmplacement() === $this) {
                $stock->setEmplacement(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ListeProductAchatFournisseur>
     */
    public function getListeProductAchatFournisseurs(): Collection
    {
        return $this->listeProductAchatFournisseurs;
    }

    public function addListeProductAchatFournisseur(ListeProductAchatFournisseur $listeProductAchatFournisseur): static
    {
        if (!$this->listeProductAchatFournisseurs->contains($listeProductAchatFournisseur)) {
            $this->listeProductAchatFournisseurs->add($listeProductAchatFournisseur);
            $listeProductAchatFournisseur->setStock($this);
        }

        return $this;
    }

    public function removeListeProductAchatFournisseur(ListeProductAchatFournisseur $listeProductAchatFournisseur): static
    {
        if ($this->listeProductAchatFournisseurs->removeElement($listeProductAchatFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($listeProductAchatFournisseur->getStock() === $this) {
                $listeProductAchatFournisseur->setStock(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MouvementProduct>
     */
    public function getMouvementProducts(): Collection
    {
        return $this->mouvementProducts;
    }

    public function addMouvementProduct(MouvementProduct $mouvementProduct): static
    {
        if (!$this->mouvementProducts->contains($mouvementProduct)) {
            $this->mouvementProducts->add($mouvementProduct);
            $mouvementProduct->setStockProduct($this);
        }

        return $this;
    }

    public function removeMouvementProduct(MouvementProduct $mouvementProduct): static
    {
        if ($this->mouvementProducts->removeElement($mouvementProduct)) {
            // set the owning side to null (unless already changed)
            if ($mouvementProduct->getStockProduct() === $this) {
                $mouvementProduct->setStockProduct(null);
            }
        }

        return $this;
    }
}
