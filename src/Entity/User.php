<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 150)]
    private ?string $prenom = null;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $typeUser = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 100)]
    private ?string $ville = null;

    #[ORM\Column(length: 150)]
    private ?string $pays = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 100)]
    private ?string $statut = null;

    #[ORM\OneToMany(targetEntity: LieuxVentes::class, mappedBy: 'gestionnaire')]
    private Collection $lieuxVentes;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LieuxVentes $lieuVente = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Region $region = null;

    #[ORM\OneToMany(targetEntity: Personnel::class, mappedBy: 'user', orphanRemoval:true, cascade:['persist', 'remove'])]
    private Collection $personnels;

    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'user', orphanRemoval:true, cascade:['persist', 'remove'])]
    private Collection $clients;

    #[ORM\OneToMany(targetEntity: Decaissement::class, mappedBy: 'client')]
    private Collection $decaissements;

    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'client')]
    private Collection $commandes;

    #[ORM\OneToMany(targetEntity: JourneeTravail::class, mappedBy: 'saisiePar')]
    private Collection $journeeTravails;

    #[ORM\OneToMany(targetEntity: Facturation::class, mappedBy: 'client')]
    private Collection $facturations;

    #[ORM\OneToMany(targetEntity: TableCommande::class, mappedBy: 'saisiePar')]
    private Collection $tableCommandes;

    #[ORM\OneToMany(targetEntity: TableCommande::class, mappedBy: 'preparePar')]
    private Collection $tableCommandesPrepa;

    #[ORM\OneToMany(targetEntity: TableCommande::class, mappedBy: 'serviePar')]
    private Collection $tableCommandesServie;

    #[ORM\OneToMany(targetEntity: ListeStock::class, mappedBy: 'responsable')]
    private Collection $listeStocks;

    #[ORM\OneToMany(targetEntity: AchatFournisseur::class, mappedBy: 'fournisseur')]
    private Collection $achatFournisseurs;

    #[ORM\OneToMany(targetEntity: MouvementCollaborateur::class, mappedBy: 'collaborateur')]
    private Collection $mouvementCollaborateurs;

    #[ORM\OneToMany(targetEntity: ListeProductAchatFournisseur::class, mappedBy: 'personnel')]
    private Collection $listeProductAchatFournisseurs;

    #[ORM\OneToMany(targetEntity: MouvementProduct::class, mappedBy: 'personnel')]
    private Collection $mouvementProducts;

    #[ORM\OneToMany(targetEntity: Depense::class, mappedBy: 'traitePar')]
    private Collection $depenses;

    #[ORM\OneToMany(targetEntity: AbsencePersonnel::class, mappedBy: 'personnel')]
    private Collection $absencePersonnels;

    #[ORM\OneToMany(targetEntity: AvanceSalaire::class, mappedBy: 'user')]
    private Collection $avanceSalaires;

    #[ORM\OneToMany(targetEntity: PrimePersonnel::class, mappedBy: 'personnel')]
    private Collection $primePersonnels;

    #[ORM\OneToMany(targetEntity: PaiementSalairePersonnel::class, mappedBy: 'personnel')]
    private Collection $paiementSalairePersonnels;

    #[ORM\OneToMany(targetEntity: TransfertFond::class, mappedBy: 'traitePar')]
    private Collection $transfertFonds;

    #[ORM\OneToMany(targetEntity: ModificationCommande::class, mappedBy: 'modifierPar')]
    private Collection $modificationCommandes;

    #[ORM\OneToMany(targetEntity: AjustementSolde::class, mappedBy: 'collaborateur')]
    private Collection $ajustementSoldes;

    #[ORM\OneToMany(targetEntity: AjustementSolde::class, mappedBy: 'traitePar')]
    private Collection $ajustementSoldesTraite;

    #[ORM\OneToMany(targetEntity: Versement::class, mappedBy: 'client')]
    private Collection $versements;

    #[ORM\OneToMany(targetEntity: ModificationFacture::class, mappedBy: 'client')]
    private Collection $modificationFactures;

    #[ORM\OneToMany(targetEntity: SuppressionFacture::class, mappedBy: 'client')]
    private Collection $suppressionFactures;

    #[ORM\OneToMany(targetEntity: MouvementCaisse::class, mappedBy: 'saisiePar')]
    private Collection $mouvementCaisses;

    #[ORM\OneToMany(targetEntity: ClotureCaisse::class, mappedBy: 'saisiePar')]
    private Collection $clotureCaisses;

    public function __construct()
    {
        $this->lieuxVentes = new ArrayCollection();
        $this->personnels = new ArrayCollection();
        $this->clients = new ArrayCollection();
        $this->decaissements = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->journeeTravails = new ArrayCollection();
        $this->facturations = new ArrayCollection();
        $this->tableCommandes = new ArrayCollection();
        $this->tableCommandesPrepa = new ArrayCollection();
        $this->tableCommandesServie = new ArrayCollection();
        $this->listeStocks = new ArrayCollection();
        $this->achatFournisseurs = new ArrayCollection();
        $this->mouvementCollaborateurs = new ArrayCollection();
        $this->listeProductAchatFournisseurs = new ArrayCollection();
        $this->mouvementProducts = new ArrayCollection();
        $this->depenses = new ArrayCollection();
        $this->absencePersonnels = new ArrayCollection();
        $this->avanceSalaires = new ArrayCollection();
        $this->primePersonnels = new ArrayCollection();
        $this->paiementSalairePersonnels = new ArrayCollection();
        $this->transfertFonds = new ArrayCollection();
        $this->modificationCommandes = new ArrayCollection();
        $this->ajustementSoldes = new ArrayCollection();
        $this->ajustementSoldesTraite = new ArrayCollection();
        $this->versements = new ArrayCollection();
        $this->modificationFactures = new ArrayCollection();
        $this->suppressionFactures = new ArrayCollection();
        $this->mouvementCaisses = new ArrayCollection();
        $this->clotureCaisses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

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

    public function getTypeUser(): ?string
    {
        return $this->typeUser;
    }

    public function setTypeUser(string $typeUser): static
    {
        $this->typeUser = $typeUser;

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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

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

    /**
     * @return Collection<int, LieuxVentes>
     */
    public function getLieuxVentes(): Collection
    {
        return $this->lieuxVentes;
    }

    public function addLieuxVente(LieuxVentes $lieuxVente): static
    {
        if (!$this->lieuxVentes->contains($lieuxVente)) {
            $this->lieuxVentes->add($lieuxVente);
            $lieuxVente->setGestionnaire($this);
        }

        return $this;
    }

    public function removeLieuxVente(LieuxVentes $lieuxVente): static
    {
        if ($this->lieuxVentes->removeElement($lieuxVente)) {
            // set the owning side to null (unless already changed)
            if ($lieuxVente->getGestionnaire() === $this) {
                $lieuxVente->setGestionnaire(null);
            }
        }

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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection<int, Personnel>
     */
    public function getPersonnels(): Collection
    {
        return $this->personnels;
    }

    public function addPersonnel(Personnel $personnel): static
    {
        if (!$this->personnels->contains($personnel)) {
            $this->personnels->add($personnel);
            $personnel->setUser($this);
        }

        return $this;
    }

    public function removePersonnel(Personnel $personnel): static
    {
        if ($this->personnels->removeElement($personnel)) {
            // set the owning side to null (unless already changed)
            if ($personnel->getUser() === $this) {
                $personnel->setUser(null);
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
            $client->setUser($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getUser() === $this) {
                $client->setUser(null);
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
            $decaissement->setClient($this);
        }

        return $this;
    }

    public function removeDecaissement(Decaissement $decaissement): static
    {
        if ($this->decaissements->removeElement($decaissement)) {
            // set the owning side to null (unless already changed)
            if ($decaissement->getClient() === $this) {
                $decaissement->setClient(null);
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
            $commande->setClient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getClient() === $this) {
                $commande->setClient(null);
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
            $journeeTravail->setSaisiePar($this);
        }

        return $this;
    }

    public function removeJourneeTravail(JourneeTravail $journeeTravail): static
    {
        if ($this->journeeTravails->removeElement($journeeTravail)) {
            // set the owning side to null (unless already changed)
            if ($journeeTravail->getSaisiePar() === $this) {
                $journeeTravail->setSaisiePar(null);
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
            $facturation->setClient($this);
        }

        return $this;
    }

    public function removeFacturation(Facturation $facturation): static
    {
        if ($this->facturations->removeElement($facturation)) {
            // set the owning side to null (unless already changed)
            if ($facturation->getClient() === $this) {
                $facturation->setClient(null);
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
            $tableCommande->setSaisiePar($this);
        }

        return $this;
    }

    public function removeTableCommande(TableCommande $tableCommande): static
    {
        if ($this->tableCommandes->removeElement($tableCommande)) {
            // set the owning side to null (unless already changed)
            if ($tableCommande->getSaisiePar() === $this) {
                $tableCommande->setSaisiePar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TableCommande>
     */
    public function getTableCommandesPrepa(): Collection
    {
        return $this->tableCommandesPrepa;
    }

    public function addTableCommandesPrepa(TableCommande $tableCommandesPrepa): static
    {
        if (!$this->tableCommandesPrepa->contains($tableCommandesPrepa)) {
            $this->tableCommandesPrepa->add($tableCommandesPrepa);
            $tableCommandesPrepa->setPreparePar($this);
        }

        return $this;
    }

    public function removeTableCommandesPrepa(TableCommande $tableCommandesPrepa): static
    {
        if ($this->tableCommandesPrepa->removeElement($tableCommandesPrepa)) {
            // set the owning side to null (unless already changed)
            if ($tableCommandesPrepa->getPreparePar() === $this) {
                $tableCommandesPrepa->setPreparePar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TableCommande>
     */
    public function getTableCommandesServie(): Collection
    {
        return $this->tableCommandesServie;
    }

    public function addTableCommandesServie(TableCommande $tableCommandesServie): static
    {
        if (!$this->tableCommandesServie->contains($tableCommandesServie)) {
            $this->tableCommandesServie->add($tableCommandesServie);
            $tableCommandesServie->setServiePar($this);
        }

        return $this;
    }

    public function removeTableCommandesServie(TableCommande $tableCommandesServie): static
    {
        if ($this->tableCommandesServie->removeElement($tableCommandesServie)) {
            // set the owning side to null (unless already changed)
            if ($tableCommandesServie->getServiePar() === $this) {
                $tableCommandesServie->setServiePar(null);
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
            $listeStock->setResponsable($this);
        }

        return $this;
    }

    public function removeListeStock(ListeStock $listeStock): static
    {
        if ($this->listeStocks->removeElement($listeStock)) {
            // set the owning side to null (unless already changed)
            if ($listeStock->getResponsable() === $this) {
                $listeStock->setResponsable(null);
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
            $achatFournisseur->setFournisseur($this);
        }

        return $this;
    }

    public function removeAchatFournisseur(AchatFournisseur $achatFournisseur): static
    {
        if ($this->achatFournisseurs->removeElement($achatFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($achatFournisseur->getFournisseur() === $this) {
                $achatFournisseur->setFournisseur(null);
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
            $mouvementCollaborateur->setCollaborateur($this);
        }

        return $this;
    }

    public function removeMouvementCollaborateur(MouvementCollaborateur $mouvementCollaborateur): static
    {
        if ($this->mouvementCollaborateurs->removeElement($mouvementCollaborateur)) {
            // set the owning side to null (unless already changed)
            if ($mouvementCollaborateur->getCollaborateur() === $this) {
                $mouvementCollaborateur->setCollaborateur(null);
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
            $listeProductAchatFournisseur->setPersonnel($this);
        }

        return $this;
    }

    public function removeListeProductAchatFournisseur(ListeProductAchatFournisseur $listeProductAchatFournisseur): static
    {
        if ($this->listeProductAchatFournisseurs->removeElement($listeProductAchatFournisseur)) {
            // set the owning side to null (unless already changed)
            if ($listeProductAchatFournisseur->getPersonnel() === $this) {
                $listeProductAchatFournisseur->setPersonnel(null);
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
            $mouvementProduct->setPersonnel($this);
        }

        return $this;
    }

    public function removeMouvementProduct(MouvementProduct $mouvementProduct): static
    {
        if ($this->mouvementProducts->removeElement($mouvementProduct)) {
            // set the owning side to null (unless already changed)
            if ($mouvementProduct->getPersonnel() === $this) {
                $mouvementProduct->setPersonnel(null);
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
            $depense->setTraitePar($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): static
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getTraitePar() === $this) {
                $depense->setTraitePar(null);
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
            $absencePersonnel->setPersonnel($this);
        }

        return $this;
    }

    public function removeAbsencePersonnel(AbsencePersonnel $absencePersonnel): static
    {
        if ($this->absencePersonnels->removeElement($absencePersonnel)) {
            // set the owning side to null (unless already changed)
            if ($absencePersonnel->getPersonnel() === $this) {
                $absencePersonnel->setPersonnel(null);
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
            $avanceSalaire->setUser($this);
        }

        return $this;
    }

    public function removeAvanceSalaire(AvanceSalaire $avanceSalaire): static
    {
        if ($this->avanceSalaires->removeElement($avanceSalaire)) {
            // set the owning side to null (unless already changed)
            if ($avanceSalaire->getUser() === $this) {
                $avanceSalaire->setUser(null);
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
            $primePersonnel->setPersonnel($this);
        }

        return $this;
    }

    public function removePrimePersonnel(PrimePersonnel $primePersonnel): static
    {
        if ($this->primePersonnels->removeElement($primePersonnel)) {
            // set the owning side to null (unless already changed)
            if ($primePersonnel->getPersonnel() === $this) {
                $primePersonnel->setPersonnel(null);
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
            $paiementSalairePersonnel->setPersonnel($this);
        }

        return $this;
    }

    public function removePaiementSalairePersonnel(PaiementSalairePersonnel $paiementSalairePersonnel): static
    {
        if ($this->paiementSalairePersonnels->removeElement($paiementSalairePersonnel)) {
            // set the owning side to null (unless already changed)
            if ($paiementSalairePersonnel->getPersonnel() === $this) {
                $paiementSalairePersonnel->setPersonnel(null);
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
            $transfertFond->setTraitePar($this);
        }

        return $this;
    }

    public function removeTransfertFond(TransfertFond $transfertFond): static
    {
        if ($this->transfertFonds->removeElement($transfertFond)) {
            // set the owning side to null (unless already changed)
            if ($transfertFond->getTraitePar() === $this) {
                $transfertFond->setTraitePar(null);
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
            $modificationCommande->setModifierPar($this);
        }

        return $this;
    }

    public function removeModificationCommande(ModificationCommande $modificationCommande): static
    {
        if ($this->modificationCommandes->removeElement($modificationCommande)) {
            // set the owning side to null (unless already changed)
            if ($modificationCommande->getModifierPar() === $this) {
                $modificationCommande->setModifierPar(null);
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
            $ajustementSolde->setCollaborateur($this);
        }

        return $this;
    }

    public function removeAjustementSolde(AjustementSolde $ajustementSolde): static
    {
        if ($this->ajustementSoldes->removeElement($ajustementSolde)) {
            // set the owning side to null (unless already changed)
            if ($ajustementSolde->getCollaborateur() === $this) {
                $ajustementSolde->setCollaborateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AjustementSolde>
     */
    public function getAjustementSoldesTraite(): Collection
    {
        return $this->ajustementSoldesTraite;
    }

    public function addAjustementSoldesTraite(AjustementSolde $ajustementSoldesTraite): static
    {
        if (!$this->ajustementSoldesTraite->contains($ajustementSoldesTraite)) {
            $this->ajustementSoldesTraite->add($ajustementSoldesTraite);
            $ajustementSoldesTraite->setTraitePar($this);
        }

        return $this;
    }

    public function removeAjustementSoldesTraite(AjustementSolde $ajustementSoldesTraite): static
    {
        if ($this->ajustementSoldesTraite->removeElement($ajustementSoldesTraite)) {
            // set the owning side to null (unless already changed)
            if ($ajustementSoldesTraite->getTraitePar() === $this) {
                $ajustementSoldesTraite->setTraitePar(null);
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
            $versement->setClient($this);
        }

        return $this;
    }

    public function removeVersement(Versement $versement): static
    {
        if ($this->versements->removeElement($versement)) {
            // set the owning side to null (unless already changed)
            if ($versement->getClient() === $this) {
                $versement->setClient(null);
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
            $modificationFacture->setClient($this);
        }

        return $this;
    }

    public function removeModificationFacture(ModificationFacture $modificationFacture): static
    {
        if ($this->modificationFactures->removeElement($modificationFacture)) {
            // set the owning side to null (unless already changed)
            if ($modificationFacture->getClient() === $this) {
                $modificationFacture->setClient(null);
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
            $suppressionFacture->setClient($this);
        }

        return $this;
    }

    public function removeSuppressionFacture(SuppressionFacture $suppressionFacture): static
    {
        if ($this->suppressionFactures->removeElement($suppressionFacture)) {
            // set the owning side to null (unless already changed)
            if ($suppressionFacture->getClient() === $this) {
                $suppressionFacture->setClient(null);
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
            $mouvementCaiss->setSaisiePar($this);
        }

        return $this;
    }

    public function removeMouvementCaiss(MouvementCaisse $mouvementCaiss): static
    {
        if ($this->mouvementCaisses->removeElement($mouvementCaiss)) {
            // set the owning side to null (unless already changed)
            if ($mouvementCaiss->getSaisiePar() === $this) {
                $mouvementCaiss->setSaisiePar(null);
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
            $clotureCaiss->setSaisiePar($this);
        }

        return $this;
    }

    public function removeClotureCaiss(ClotureCaisse $clotureCaiss): static
    {
        if ($this->clotureCaisses->removeElement($clotureCaiss)) {
            // set the owning side to null (unless already changed)
            if ($clotureCaiss->getSaisiePar() === $this) {
                $clotureCaiss->setSaisiePar(null);
            }
        }

        return $this;
    }
}
