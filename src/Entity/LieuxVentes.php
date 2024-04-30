<?php

namespace App\Entity;

use App\Repository\LieuxVentesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LieuxVentesRepository::class)]
class LieuxVentes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lieuxVentes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprise $entreprise = null;

    #[ORM\ManyToOne(inversedBy: 'lieuxVentes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $gestionnaire = null;

    #[ORM\Column(length: 150)]
    private ?string $lieu = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 100)]
    private ?string $pays = null;

    #[ORM\Column(length: 100)]
    private ?string $region = null;

    #[ORM\Column(length: 100)]
    private ?string $ville = null;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $latitude = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $longitude = null;

    #[ORM\Column(length: 10)]
    private ?string $initial = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeCommerce = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'lieuVente')]
    private Collection $users;

    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'rattachement')]
    private Collection $clients;

    #[ORM\OneToMany(targetEntity: PointDeVente::class, mappedBy: 'lieuVente')]
    private Collection $pointDeVentes;

    #[ORM\OneToMany(targetEntity: Decaissement::class, mappedBy: 'lieuVente')]
    private Collection $decaissements;
    
    #[ORM\OneToMany(targetEntity: JourneeTravail::class, mappedBy: 'lieuVente')]
    private Collection $journeeTravails;

    #[ORM\OneToMany(targetEntity: Facturation::class, mappedBy: 'lieuVente')]
    private Collection $facturations;

    #[ORM\OneToMany(targetEntity: Salle::class, mappedBy: 'lieuVente')]
    private Collection $salles;

    #[ORM\OneToMany(targetEntity: Table::class, mappedBy: 'lieuVente')]
    private Collection $tables;

    #[ORM\OneToMany(targetEntity: MenuVente::class, mappedBy: 'lieuVente')]
    private Collection $menuVentes;

    #[ORM\OneToMany(targetEntity: TableCommande::class, mappedBy: 'lieuVente')]
    private Collection $tableCommandes;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'lieuVente')]
    private Collection $products;

    #[ORM\OneToMany(targetEntity: ListeStock::class, mappedBy: 'lieuVente')]
    private Collection $listeStocks;

    #[ORM\OneToMany(targetEntity: AchatFournisseur::class, mappedBy: 'lieuVente')]
    private Collection $achatFournisseurs;

    #[ORM\OneToMany(targetEntity: MouvementCaisse::class, mappedBy: 'lieuVente')]
    private Collection $mouvementCaisses;

    #[ORM\OneToMany(targetEntity: MouvementCollaborateur::class, mappedBy: 'lieuVente')]
    private Collection $mouvementCollaborateurs;

    #[ORM\OneToMany(targetEntity: MouvementProduct::class, mappedBy: 'lieuVente')]
    private Collection $mouvementProducts;

    #[ORM\OneToMany(targetEntity: Depense::class, mappedBy: 'lieuVente')]
    private Collection $depenses;

    #[ORM\OneToMany(targetEntity: AbsencePersonnel::class, mappedBy: 'lieuVente')]
    private Collection $absencePersonnels;

    #[ORM\OneToMany(targetEntity: AvanceSalaire::class, mappedBy: 'lieuVente')]
    private Collection $avanceSalaires;

    #[ORM\OneToMany(targetEntity: PrimePersonnel::class, mappedBy: 'lieuVente')]
    private Collection $primePersonnels;

    #[ORM\OneToMany(targetEntity: PaiementSalairePersonnel::class, mappedBy: 'lieuVente')]
    private Collection $paiementSalairePersonnels;

    #[ORM\OneToMany(targetEntity: TransfertFond::class, mappedBy: 'lieuVente')]
    private Collection $transfertFonds;

    #[ORM\OneToMany(targetEntity: AjustementSolde::class, mappedBy: 'lieuVente')]
    private Collection $ajustementSoldes;

    #[ORM\OneToMany(targetEntity: Versement::class, mappedBy: 'lieuVente')]
    private Collection $versements;

    #[ORM\OneToMany(targetEntity: ModificationFacture::class, mappedBy: 'lieuVente')]
    private Collection $modificationFactures;

    #[ORM\OneToMany(targetEntity: SuppressionFacture::class, mappedBy: 'lieuVente')]
    private Collection $suppressionFactures;

    #[ORM\OneToMany(targetEntity: ClotureCaisse::class, mappedBy: 'lieuVente')]
    private Collection $clotureCaisses;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->pointDeVentes = new ArrayCollection();
        $this->decaissements = new ArrayCollection();
        $this->journeeTravails = new ArrayCollection();
        $this->facturations = new ArrayCollection();
        $this->salles = new ArrayCollection();
        $this->tables = new ArrayCollection();
        $this->menuVentes = new ArrayCollection();
        $this->tableCommandes = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->listeStocks = new ArrayCollection();
        $this->achatFournisseurs = new ArrayCollection();
        $this->mouvementCaisses = new ArrayCollection();
        $this->mouvementCollaborateurs = new ArrayCollection();
        $this->mouvementProducts = new ArrayCollection();
        $this->depenses = new ArrayCollection();
        $this->absencePersonnels = new ArrayCollection();
        $this->avanceSalaires = new ArrayCollection();
        $this->primePersonnels = new ArrayCollection();
        $this->paiementSalairePersonnels = new ArrayCollection();
        $this->transfertFonds = new ArrayCollection();
        $this->ajustementSoldes = new ArrayCollection();
        $this->versements = new ArrayCollection();
        $this->modificationFactures = new ArrayCollection();
        $this->suppressionFactures = new ArrayCollection();
        $this->clotureCaisses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): static
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getGestionnaire(): ?User
    {
        return $this->gestionnaire;
    }

    public function setGestionnaire(?User $gestionnaire): static
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

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

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getInitial(): ?string
    {
        return $this->initial;
    }

    public function setInitial(string $initial): static
    {
        $this->initial = $initial;

        return $this;
    }

    public function getTypeCommerce(): ?string
    {
        return $this->typeCommerce;
    }

    public function setTypeCommerce(?string $typeCommerce): static
    {
        $this->typeCommerce = $typeCommerce;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setLieuVente($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getLieuVente() === $this) {
                $user->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setRattachement($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getRattachement() === $this) {
                $client->setRattachement(null);
            }
        }

        return $this;
    }

    

    /**
     * @return Collection<int, PointDeVente>
     */
    public function getPointDeVentes(): Collection
    {
        return $this->pointDeVentes;
    }

    public function addPointDeVente(PointDeVente $pointDeVente): static
    {
        if (!$this->pointDeVentes->contains($pointDeVente)) {
            $this->pointDeVentes->add($pointDeVente);
            $pointDeVente->setLieuVente($this);
        }

        return $this;
    }

    public function removePointDeVente(PointDeVente $pointDeVente): static
    {
        if ($this->pointDeVentes->removeElement($pointDeVente)) {
            // set the owning side to null (unless already changed)
            if ($pointDeVente->getLieuVente() === $this) {
                $pointDeVente->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Decaissement>
     */
    public function getDecaissements(): Collection
    {
        return $this->decaissements;
    }

    public function addDecaissement(Decaissement $decaissement): static
    {
        if (!$this->decaissements->contains($decaissement)) {
            $this->decaissements->add($decaissement);
            $decaissement->setLieuVente($this);
        }

        return $this;
    }

    public function removeDecaissement(Decaissement $decaissement): static
    {
        if ($this->decaissements->removeElement($decaissement)) {
            // set the owning side to null (unless already changed)
            if ($decaissement->getLieuVente() === $this) {
                $decaissement->setLieuVente(null);
            }
        }

        return $this;
    }
       

    /**
     * @return Collection<int, JourneeTravail>
     */
    public function getJourneeTravails(): Collection
    {
        return $this->journeeTravails;
    }

    public function addJourneeTravail(JourneeTravail $journeeTravail): static
    {
        if (!$this->journeeTravails->contains($journeeTravail)) {
            $this->journeeTravails->add($journeeTravail);
            $journeeTravail->setLieuVente($this);
        }

        return $this;
    }

    public function removeJourneeTravail(JourneeTravail $journeeTravail): static
    {
        if ($this->journeeTravails->removeElement($journeeTravail)) {
            // set the owning side to null (unless already changed)
            if ($journeeTravail->getLieuVente() === $this) {
                $journeeTravail->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facturation>
     */
    public function getFacturations(): Collection
    {
        return $this->facturations;
    }

    public function addFacturation(Facturation $facturation): static
    {
        if (!$this->facturations->contains($facturation)) {
            $this->facturations->add($facturation);
            $facturation->setLieuVente($this);
        }

        return $this;
    }

    public function removeFacturation(Facturation $facturation): static
    {
        if ($this->facturations->removeElement($facturation)) {
            // set the owning side to null (unless already changed)
            if ($facturation->getLieuVente() === $this) {
                $facturation->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Salle>
     */
    public function getSalles(): Collection
    {
        return $this->salles;
    }

    public function addSalle(Salle $salle): static
    {
        if (!$this->salles->contains($salle)) {
            $this->salles->add($salle);
            $salle->setLieuVente($this);
        }

        return $this;
    }

    public function removeSalle(Salle $salle): static
    {
        if ($this->salles->removeElement($salle)) {
            // set the owning side to null (unless already changed)
            if ($salle->getLieuVente() === $this) {
                $salle->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Table>
     */
    public function getTables(): Collection
    {
        return $this->tables;
    }

    public function addTable(Table $table): static
    {
        if (!$this->tables->contains($table)) {
            $this->tables->add($table);
            $table->setLieuVente($this);
        }

        return $this;
    }

    public function removeTable(Table $table): static
    {
        if ($this->tables->removeElement($table)) {
            // set the owning side to null (unless already changed)
            if ($table->getLieuVente() === $this) {
                $table->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MenuVente>
     */
    public function getMenuVentes(): Collection
    {
        return $this->menuVentes;
    }

    public function addMenuVente(MenuVente $menuVente): static
    {
        if (!$this->menuVentes->contains($menuVente)) {
            $this->menuVentes->add($menuVente);
            $menuVente->setLieuVente($this);
        }

        return $this;
    }

    public function removeMenuVente(MenuVente $menuVente): static
    {
        if ($this->menuVentes->removeElement($menuVente)) {
            // set the owning side to null (unless already changed)
            if ($menuVente->getLieuVente() === $this) {
                $menuVente->setLieuVente(null);
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
            $tableCommande->setLieuVente($this);
        }

        return $this;
    }

    public function removeTableCommande(TableCommande $tableCommande): static
    {
        if ($this->tableCommandes->removeElement($tableCommande)) {
            // set the owning side to null (unless already changed)
            if ($tableCommande->getLieuVente() === $this) {
                $tableCommande->setLieuVente(null);
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
            $product->setLieuVente($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getLieuVente() === $this) {
                $product->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ListeStock>
     */
    public function getListeStocks(): Collection
    {
        return $this->listeStocks;
    }

    public function addListeStock(ListeStock $listeStock): static
    {
        if (!$this->listeStocks->contains($listeStock)) {
            $this->listeStocks->add($listeStock);
            $listeStock->setLieuVente($this);
        }

        return $this;
    }

    public function removeListeStock(ListeStock $listeStock): static
    {
        if ($this->listeStocks->removeElement($listeStock)) {
            // set the owning side to null (unless already changed)
            if ($listeStock->getLieuVente() === $this) {
                $listeStock->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AchatFournisseur>
     */
    public function getAchatFournisseurs(): Collection
    {
        return $this->achatFournisseurs;
    }

    public function addAchatFournisseur(AchatFournisseur $achatFournisseur): static
    {
        if (!$this->achatFournisseurs->contains($achatFournisseur)) {
            $this->achatFournisseurs->add($achatFournisseur);
            $achatFournisseur->setLieuVente($this);
        }

        return $this;
    }

    public function removeAchatFournisseur(AchatFournisseur $achatFournisseur): static
    {
        if ($this->achatFournisseurs->removeElement($achatFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($achatFournisseur->getLieuVente() === $this) {
                $achatFournisseur->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MouvementCaisse>
     */
    public function getMouvementCaisses(): Collection
    {
        return $this->mouvementCaisses;
    }

    public function addMouvementCaiss(MouvementCaisse $mouvementCaiss): static
    {
        if (!$this->mouvementCaisses->contains($mouvementCaiss)) {
            $this->mouvementCaisses->add($mouvementCaiss);
            $mouvementCaiss->setLieuVente($this);
        }

        return $this;
    }

    public function removeMouvementCaiss(MouvementCaisse $mouvementCaiss): static
    {
        if ($this->mouvementCaisses->removeElement($mouvementCaiss)) {
            // set the owning side to null (unless already changed)
            if ($mouvementCaiss->getLieuVente() === $this) {
                $mouvementCaiss->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MouvementCollaborateur>
     */
    public function getMouvementCollaborateurs(): Collection
    {
        return $this->mouvementCollaborateurs;
    }

    public function addMouvementCollaborateur(MouvementCollaborateur $mouvementCollaborateur): static
    {
        if (!$this->mouvementCollaborateurs->contains($mouvementCollaborateur)) {
            $this->mouvementCollaborateurs->add($mouvementCollaborateur);
            $mouvementCollaborateur->setLieuVente($this);
        }

        return $this;
    }

    public function removeMouvementCollaborateur(MouvementCollaborateur $mouvementCollaborateur): static
    {
        if ($this->mouvementCollaborateurs->removeElement($mouvementCollaborateur)) {
            // set the owning side to null (unless already changed)
            if ($mouvementCollaborateur->getLieuVente() === $this) {
                $mouvementCollaborateur->setLieuVente(null);
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
            $mouvementProduct->setLieuVente($this);
        }

        return $this;
    }

    public function removeMouvementProduct(MouvementProduct $mouvementProduct): static
    {
        if ($this->mouvementProducts->removeElement($mouvementProduct)) {
            // set the owning side to null (unless already changed)
            if ($mouvementProduct->getLieuVente() === $this) {
                $mouvementProduct->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Depense>
     */
    public function getDepenses(): Collection
    {
        return $this->depenses;
    }

    public function addDepense(Depense $depense): static
    {
        if (!$this->depenses->contains($depense)) {
            $this->depenses->add($depense);
            $depense->setLieuVente($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): static
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getLieuVente() === $this) {
                $depense->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AbsencePersonnel>
     */
    public function getAbsencePersonnels(): Collection
    {
        return $this->absencePersonnels;
    }

    public function addAbsencePersonnel(AbsencePersonnel $absencePersonnel): static
    {
        if (!$this->absencePersonnels->contains($absencePersonnel)) {
            $this->absencePersonnels->add($absencePersonnel);
            $absencePersonnel->setLieuVente($this);
        }

        return $this;
    }

    public function removeAbsencePersonnel(AbsencePersonnel $absencePersonnel): static
    {
        if ($this->absencePersonnels->removeElement($absencePersonnel)) {
            // set the owning side to null (unless already changed)
            if ($absencePersonnel->getLieuVente() === $this) {
                $absencePersonnel->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AvanceSalaire>
     */
    public function getAvanceSalaires(): Collection
    {
        return $this->avanceSalaires;
    }

    public function addAvanceSalaire(AvanceSalaire $avanceSalaire): static
    {
        if (!$this->avanceSalaires->contains($avanceSalaire)) {
            $this->avanceSalaires->add($avanceSalaire);
            $avanceSalaire->setLieuVente($this);
        }

        return $this;
    }

    public function removeAvanceSalaire(AvanceSalaire $avanceSalaire): static
    {
        if ($this->avanceSalaires->removeElement($avanceSalaire)) {
            // set the owning side to null (unless already changed)
            if ($avanceSalaire->getLieuVente() === $this) {
                $avanceSalaire->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PrimePersonnel>
     */
    public function getPrimePersonnels(): Collection
    {
        return $this->primePersonnels;
    }

    public function addPrimePersonnel(PrimePersonnel $primePersonnel): static
    {
        if (!$this->primePersonnels->contains($primePersonnel)) {
            $this->primePersonnels->add($primePersonnel);
            $primePersonnel->setLieuVente($this);
        }

        return $this;
    }

    public function removePrimePersonnel(PrimePersonnel $primePersonnel): static
    {
        if ($this->primePersonnels->removeElement($primePersonnel)) {
            // set the owning side to null (unless already changed)
            if ($primePersonnel->getLieuVente() === $this) {
                $primePersonnel->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PaiementSalairePersonnel>
     */
    public function getPaiementSalairePersonnels(): Collection
    {
        return $this->paiementSalairePersonnels;
    }

    public function addPaiementSalairePersonnel(PaiementSalairePersonnel $paiementSalairePersonnel): static
    {
        if (!$this->paiementSalairePersonnels->contains($paiementSalairePersonnel)) {
            $this->paiementSalairePersonnels->add($paiementSalairePersonnel);
            $paiementSalairePersonnel->setLieuVente($this);
        }

        return $this;
    }

    public function removePaiementSalairePersonnel(PaiementSalairePersonnel $paiementSalairePersonnel): static
    {
        if ($this->paiementSalairePersonnels->removeElement($paiementSalairePersonnel)) {
            // set the owning side to null (unless already changed)
            if ($paiementSalairePersonnel->getLieuVente() === $this) {
                $paiementSalairePersonnel->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TransfertFond>
     */
    public function getTransfertFonds(): Collection
    {
        return $this->transfertFonds;
    }

    public function addTransfertFond(TransfertFond $transfertFond): static
    {
        if (!$this->transfertFonds->contains($transfertFond)) {
            $this->transfertFonds->add($transfertFond);
            $transfertFond->setLieuVente($this);
        }

        return $this;
    }

    public function removeTransfertFond(TransfertFond $transfertFond): static
    {
        if ($this->transfertFonds->removeElement($transfertFond)) {
            // set the owning side to null (unless already changed)
            if ($transfertFond->getLieuVente() === $this) {
                $transfertFond->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AjustementSolde>
     */
    public function getAjustementSoldes(): Collection
    {
        return $this->ajustementSoldes;
    }

    public function addAjustementSolde(AjustementSolde $ajustementSolde): static
    {
        if (!$this->ajustementSoldes->contains($ajustementSolde)) {
            $this->ajustementSoldes->add($ajustementSolde);
            $ajustementSolde->setLieuVente($this);
        }

        return $this;
    }

    public function removeAjustementSolde(AjustementSolde $ajustementSolde): static
    {
        if ($this->ajustementSoldes->removeElement($ajustementSolde)) {
            // set the owning side to null (unless already changed)
            if ($ajustementSolde->getLieuVente() === $this) {
                $ajustementSolde->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Versement>
     */
    public function getVersements(): Collection
    {
        return $this->versements;
    }

    public function addVersement(Versement $versement): static
    {
        if (!$this->versements->contains($versement)) {
            $this->versements->add($versement);
            $versement->setLieuVente($this);
        }

        return $this;
    }

    public function removeVersement(Versement $versement): static
    {
        if ($this->versements->removeElement($versement)) {
            // set the owning side to null (unless already changed)
            if ($versement->getLieuVente() === $this) {
                $versement->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ModificationFacture>
     */
    public function getModificationFactures(): Collection
    {
        return $this->modificationFactures;
    }

    public function addModificationFacture(ModificationFacture $modificationFacture): static
    {
        if (!$this->modificationFactures->contains($modificationFacture)) {
            $this->modificationFactures->add($modificationFacture);
            $modificationFacture->setLieuVente($this);
        }

        return $this;
    }

    public function removeModificationFacture(ModificationFacture $modificationFacture): static
    {
        if ($this->modificationFactures->removeElement($modificationFacture)) {
            // set the owning side to null (unless already changed)
            if ($modificationFacture->getLieuVente() === $this) {
                $modificationFacture->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SuppressionFacture>
     */
    public function getSuppressionFactures(): Collection
    {
        return $this->suppressionFactures;
    }

    public function addSuppressionFacture(SuppressionFacture $suppressionFacture): static
    {
        if (!$this->suppressionFactures->contains($suppressionFacture)) {
            $this->suppressionFactures->add($suppressionFacture);
            $suppressionFacture->setLieuVente($this);
        }

        return $this;
    }

    public function removeSuppressionFacture(SuppressionFacture $suppressionFacture): static
    {
        if ($this->suppressionFactures->removeElement($suppressionFacture)) {
            // set the owning side to null (unless already changed)
            if ($suppressionFacture->getLieuVente() === $this) {
                $suppressionFacture->setLieuVente(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ClotureCaisse>
     */
    public function getClotureCaisses(): Collection
    {
        return $this->clotureCaisses;
    }

    public function addClotureCaiss(ClotureCaisse $clotureCaiss): static
    {
        if (!$this->clotureCaisses->contains($clotureCaiss)) {
            $this->clotureCaisses->add($clotureCaiss);
            $clotureCaiss->setLieuVente($this);
        }

        return $this;
    }

    public function removeClotureCaiss(ClotureCaisse $clotureCaiss): static
    {
        if ($this->clotureCaisses->removeElement($clotureCaiss)) {
            // set the owning side to null (unless already changed)
            if ($clotureCaiss->getLieuVente() === $this) {
                $clotureCaiss->setLieuVente(null);
            }
        }

        return $this;
    }

   
}
