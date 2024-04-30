<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name:"type", type:"string")]
#[ORM\DiscriminatorMap([
    "plat" => Plat::class,
    'boisson' => Boisson::class,
    'chicha' => Chicha::class,
    'cocktail' => Cocktail::class,
    'entree' => Entree::class,
    'dessert' => Dessert::class,
    'ingredient' => Ingredient::class
])]
abstract class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 13, scale: 2, nullable: true)]
    private ?string $prixVente = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Service $service = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: true)]
    private ?LieuxVentes $lieuVente = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToMany(targetEntity: TableCommande::class, mappedBy: 'product')]
    private Collection $tableCommandes;

    #[ORM\OneToMany(targetEntity: ListeProductAchatFournisseur::class, mappedBy: 'product')]
    private Collection $listeProductAchatFournisseurs;

    #[ORM\OneToMany(targetEntity: MouvementProduct::class, mappedBy: 'product')]
    private Collection $mouvementProducts;

    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'product')]
    private Collection $stocks;

    #[ORM\OneToMany(targetEntity: CommandeProduct::class, mappedBy: 'product')]
    private Collection $commandeProducts;

    #[ORM\OneToMany(targetEntity: Recette::class, mappedBy: 'product')]
    private Collection $recettes;

    #[ORM\OneToMany(targetEntity: Recette::class, mappedBy: 'ingredient')]
    private Collection $recettesIngredient;

    #[ORM\OneToMany(targetEntity: LiaisonProduct::class, mappedBy: 'product1', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $liaisonProducts;

    #[ORM\OneToMany(targetEntity: LiaisonProduct::class, mappedBy: 'product2', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $liaisonProducts2;

    #[ORM\OneToMany(targetEntity: ModificationCommande::class, mappedBy: 'product')]
    private Collection $modificationCommandes;

    public function __construct()
    {
        $this->tableCommandes = new ArrayCollection();
        $this->listeProductAchatFournisseurs = new ArrayCollection();
        $this->mouvementProducts = new ArrayCollection();
        $this->stocks = new ArrayCollection();
        $this->commandeProducts = new ArrayCollection();
        $this->recettes = new ArrayCollection();
        $this->recettesIngredient = new ArrayCollection();
        $this->liaisonProducts = new ArrayCollection();
        $this->liaisonProducts2 = new ArrayCollection();
        $this->modificationCommandes = new ArrayCollection();
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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixVente(): ?string
    {
        return $this->prixVente;
    }

    public function setPrixVente(string $prixVente): static
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

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
     * @return Collection<int, TableCommande>
     */
    public function getTableCommandes(): Collection
    {
        return $this->tableCommandes;
    }

    public function addTableCommande(TableCommande $tableCommande): static
    {
        if (!$this->tableCommandes->contains($tableCommande)) {
            $this->tableCommandes->add($tableCommande);
            $tableCommande->setProduct($this);
        }

        return $this;
    }

    public function removeTableCommande(TableCommande $tableCommande): static
    {
        if ($this->tableCommandes->removeElement($tableCommande)) {
            // set the owning side to null (unless already changed)
            if ($tableCommande->getProduct() === $this) {
                $tableCommande->setProduct(null);
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
            $listeProductAchatFournisseur->setProduct($this);
        }

        return $this;
    }

    public function removeListeProductAchatFournisseur(ListeProductAchatFournisseur $listeProductAchatFournisseur): static
    {
        if ($this->listeProductAchatFournisseurs->removeElement($listeProductAchatFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($listeProductAchatFournisseur->getProduct() === $this) {
                $listeProductAchatFournisseur->setProduct(null);
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
            $mouvementProduct->setProduct($this);
        }

        return $this;
    }

    public function removeMouvementProduct(MouvementProduct $mouvementProduct): static
    {
        if ($this->mouvementProducts->removeElement($mouvementProduct)) {
            // set the owning side to null (unless already changed)
            if ($mouvementProduct->getProduct() === $this) {
                $mouvementProduct->setProduct(null);
            }
        }

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
            $stock->setProduct($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getProduct() === $this) {
                $stock->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeProduct>
     */
    public function getCommandeProducts(): Collection
    {
        return $this->commandeProducts;
    }

    public function addCommandeProduct(CommandeProduct $commandeProduct): static
    {
        if (!$this->commandeProducts->contains($commandeProduct)) {
            $this->commandeProducts->add($commandeProduct);
            $commandeProduct->setProduct($this);
        }

        return $this;
    }

    public function removeCommandeProduct(CommandeProduct $commandeProduct): static
    {
        if ($this->commandeProducts->removeElement($commandeProduct)) {
            // set the owning side to null (unless already changed)
            if ($commandeProduct->getProduct() === $this) {
                $commandeProduct->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): static
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->setProduct($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getProduct() === $this) {
                $recette->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecettesIngredient(): Collection
    {
        return $this->recettesIngredient;
    }

    public function addRecettesIngredient(Recette $recettesIngredient): static
    {
        if (!$this->recettesIngredient->contains($recettesIngredient)) {
            $this->recettesIngredient->add($recettesIngredient);
            $recettesIngredient->setIngredient($this);
        }

        return $this;
    }

    public function removeRecettesIngredient(Recette $recettesIngredient): static
    {
        if ($this->recettesIngredient->removeElement($recettesIngredient)) {
            // set the owning side to null (unless already changed)
            if ($recettesIngredient->getIngredient() === $this) {
                $recettesIngredient->setIngredient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LiaisonProduct>
     */
    public function getLiaisonProducts(): Collection
    {
        return $this->liaisonProducts;
    }

    public function addLiaisonProduct(LiaisonProduct $liaisonProduct): static
    {
        if (!$this->liaisonProducts->contains($liaisonProduct)) {
            $this->liaisonProducts->add($liaisonProduct);
            $liaisonProduct->setProduct1($this);
        }

        return $this;
    }

    public function removeLiaisonProduct(LiaisonProduct $liaisonProduct): static
    {
        if ($this->liaisonProducts->removeElement($liaisonProduct)) {
            // set the owning side to null (unless already changed)
            if ($liaisonProduct->getProduct1() === $this) {
                $liaisonProduct->setProduct1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LiaisonProduct>
     */
    public function getLiaisonProducts2(): Collection
    {
        return $this->liaisonProducts2;
    }

    public function addLiaisonProducts2(LiaisonProduct $liaisonProducts2): static
    {
        if (!$this->liaisonProducts2->contains($liaisonProducts2)) {
            $this->liaisonProducts2->add($liaisonProducts2);
            $liaisonProducts2->setProduct2($this);
        }

        return $this;
    }

    public function removeLiaisonProducts2(LiaisonProduct $liaisonProducts2): static
    {
        if ($this->liaisonProducts2->removeElement($liaisonProducts2)) {
            // set the owning side to null (unless already changed)
            if ($liaisonProducts2->getProduct2() === $this) {
                $liaisonProducts2->setProduct2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ModificationCommande>
     */
    public function getModificationCommandes(): Collection
    {
        return $this->modificationCommandes;
    }

    public function addModificationCommande(ModificationCommande $modificationCommande): static
    {
        if (!$this->modificationCommandes->contains($modificationCommande)) {
            $this->modificationCommandes->add($modificationCommande);
            $modificationCommande->setProduct($this);
        }

        return $this;
    }

    public function removeModificationCommande(ModificationCommande $modificationCommande): static
    {
        if ($this->modificationCommandes->removeElement($modificationCommande)) {
            // set the owning side to null (unless already changed)
            if ($modificationCommande->getProduct() === $this) {
                $modificationCommande->setProduct(null);
            }
        }

        return $this;
    }

    
}
