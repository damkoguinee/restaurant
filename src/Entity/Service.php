<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\OneToMany(targetEntity: Ecran::class, mappedBy: 'service')]
    private Collection $ecrans;

    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'service')]
    private Collection $commandes;

    #[ORM\OneToMany(targetEntity: TableCommande::class, mappedBy: 'service')]
    private Collection $tableCommandes;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'service')]
    private Collection $products;

    public function __construct()
    {
        $this->ecrans = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->tableCommandes = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    /**
     * @return Collection<int, Ecran>
     */
    public function getEcrans(): Collection
    {
        return $this->ecrans;
    }

    public function addEcran(Ecran $ecran): static
    {
        if (!$this->ecrans->contains($ecran)) {
            $this->ecrans->add($ecran);
            $ecran->setService($this);
        }

        return $this;
    }

    public function removeEcran(Ecran $ecran): static
    {
        if ($this->ecrans->removeElement($ecran)) {
            // set the owning side to null (unless already changed)
            if ($ecran->getService() === $this) {
                $ecran->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setService($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getService() === $this) {
                $commande->setService(null);
            }
        }

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
            $tableCommande->setService($this);
        }

        return $this;
    }

    public function removeTableCommande(TableCommande $tableCommande): static
    {
        if ($this->tableCommandes->removeElement($tableCommande)) {
            // set the owning side to null (unless already changed)
            if ($tableCommande->getService() === $this) {
                $tableCommande->setService(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setService($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getService() === $this) {
                $product->setService(null);
            }
        }

        return $this;
    }

    
}
