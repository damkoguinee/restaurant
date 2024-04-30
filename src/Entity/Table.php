<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TableRepository::class)]
#[ORM\Table(name: '`table`')]
class Table
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(nullable: true)]
    private ?int $capacite = null;

    #[ORM\Column(length: 50)]
    private ?string $etat = null;

    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'tableClient')]
    private Collection $commandes;

    #[ORM\ManyToOne(inversedBy: 'tables')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salle $salle = null;

    #[ORM\ManyToOne(inversedBy: 'tables')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LieuxVentes $lieuVente = null;

    #[ORM\OneToMany(targetEntity: TableCommande::class, mappedBy: 'emplacement')]
    private Collection $tableCommandes;

    #[ORM\OneToMany(targetEntity: ModificationCommande::class, mappedBy: 'emplacement')]
    private Collection $modificationCommandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->tableCommandes = new ArrayCollection();
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

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(?int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

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
            $commande->setTableClient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getTableClient() === $this) {
                $commande->setTableClient(null);
            }
        }

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): static
    {
        $this->salle = $salle;

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
            $tableCommande->setEmplacement($this);
        }

        return $this;
    }

    public function removeTableCommande(TableCommande $tableCommande): static
    {
        if ($this->tableCommandes->removeElement($tableCommande)) {
            // set the owning side to null (unless already changed)
            if ($tableCommande->getEmplacement() === $this) {
                $tableCommande->setEmplacement(null);
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
            $modificationCommande->setEmplacement($this);
        }

        return $this;
    }

    public function removeModificationCommande(ModificationCommande $modificationCommande): static
    {
        if ($this->modificationCommandes->removeElement($modificationCommande)) {
            // set the owning side to null (unless already changed)
            if ($modificationCommande->getEmplacement() === $this) {
                $modificationCommande->setEmplacement(null);
            }
        }

        return $this;
    }
}
