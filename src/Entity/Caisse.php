<?php

namespace App\Entity;

use App\Repository\CaisseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaisseRepository::class)]
class Caisse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'caisses')]
    private ?PointDeVente $pointDeVente = null;

    #[ORM\Column(length: 100)]
    private ?string $designation = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroCompte = null;

    #[ORM\OneToMany(targetEntity: Decaissement::class, mappedBy: 'compteDecaisser')]
    private Collection $decaissements;

    #[ORM\OneToMany(targetEntity: MouvementCaisse::class, mappedBy: 'caisse')]
    private Collection $mouvementCaisses;

    #[ORM\OneToMany(targetEntity: MouvementCollaborateur::class, mappedBy: 'caisse')]
    private Collection $mouvementCollaborateurs;

    #[ORM\OneToMany(targetEntity: Depense::class, mappedBy: 'caisseRetrait')]
    private Collection $depenses;

    #[ORM\OneToMany(targetEntity: AvanceSalaire::class, mappedBy: 'caisse')]
    private Collection $avanceSalaires;

    #[ORM\OneToMany(targetEntity: PaiementSalairePersonnel::class, mappedBy: 'compteRetrait')]
    private Collection $paiementSalairePersonnels;

    #[ORM\OneToMany(targetEntity: TransfertFond::class, mappedBy: 'caisseDepart')]
    private Collection $transfertFonds;

    #[ORM\OneToMany(targetEntity: Facturation::class, mappedBy: 'caisse')]
    private Collection $facturations;

    #[ORM\OneToMany(targetEntity: Versement::class, mappedBy: 'compte')]
    private Collection $versements;

    #[ORM\OneToMany(targetEntity: ModificationFacture::class, mappedBy: 'caisse')]
    private Collection $modificationFactures;

    #[ORM\OneToMany(targetEntity: SuppressionFacture::class, mappedBy: 'caisse')]
    private Collection $suppressionFactures;

    #[ORM\OneToMany(targetEntity: ClotureCaisse::class, mappedBy: 'caisse')]
    private Collection $clotureCaisses;

    public function __construct()
    {
        $this->decaissements = new ArrayCollection();
        $this->mouvementCaisses = new ArrayCollection();
        $this->mouvementCollaborateurs = new ArrayCollection();
        $this->depenses = new ArrayCollection();
        $this->avanceSalaires = new ArrayCollection();
        $this->paiementSalairePersonnels = new ArrayCollection();
        $this->transfertFonds = new ArrayCollection();
        $this->facturations = new ArrayCollection();
        $this->versements = new ArrayCollection();
        $this->modificationFactures = new ArrayCollection();
        $this->suppressionFactures = new ArrayCollection();
        $this->clotureCaisses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointDeVente(): ?PointDeVente
    {
        return $this->pointDeVente;
    }

    public function setPointDeVente(?PointDeVente $pointDeVente): static
    {
        $this->pointDeVente = $pointDeVente;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

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

    public function getNumeroCompte(): ?string
    {
        return $this->numeroCompte;
    }

    public function setNumeroCompte(?string $numeroCompte): static
    {
        $this->numeroCompte = $numeroCompte;

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
            $decaissement->setCompteDecaisser($this);
        }

        return $this;
    }

    public function removeDecaissement(Decaissement $decaissement): static
    {
        if ($this->decaissements->removeElement($decaissement)) {
            // set the owning side to null (unless already changed)
            if ($decaissement->getCompteDecaisser() === $this) {
                $decaissement->setCompteDecaisser(null);
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
            $mouvementCaiss->setCaisse($this);
        }

        return $this;
    }

    public function removeMouvementCaiss(MouvementCaisse $mouvementCaiss): static
    {
        if ($this->mouvementCaisses->removeElement($mouvementCaiss)) {
            // set the owning side to null (unless already changed)
            if ($mouvementCaiss->getCaisse() === $this) {
                $mouvementCaiss->setCaisse(null);
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
            $mouvementCollaborateur->setCaisse($this);
        }

        return $this;
    }

    public function removeMouvementCollaborateur(MouvementCollaborateur $mouvementCollaborateur): static
    {
        if ($this->mouvementCollaborateurs->removeElement($mouvementCollaborateur)) {
            // set the owning side to null (unless already changed)
            if ($mouvementCollaborateur->getCaisse() === $this) {
                $mouvementCollaborateur->setCaisse(null);
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
            $depense->setCaisseRetrait($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): static
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getCaisseRetrait() === $this) {
                $depense->setCaisseRetrait(null);
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
            $avanceSalaire->setCaisse($this);
        }

        return $this;
    }

    public function removeAvanceSalaire(AvanceSalaire $avanceSalaire): static
    {
        if ($this->avanceSalaires->removeElement($avanceSalaire)) {
            // set the owning side to null (unless already changed)
            if ($avanceSalaire->getCaisse() === $this) {
                $avanceSalaire->setCaisse(null);
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
            $paiementSalairePersonnel->setCompteRetrait($this);
        }

        return $this;
    }

    public function removePaiementSalairePersonnel(PaiementSalairePersonnel $paiementSalairePersonnel): static
    {
        if ($this->paiementSalairePersonnels->removeElement($paiementSalairePersonnel)) {
            // set the owning side to null (unless already changed)
            if ($paiementSalairePersonnel->getCompteRetrait() === $this) {
                $paiementSalairePersonnel->setCompteRetrait(null);
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
            $transfertFond->setCaisseDepart($this);
        }

        return $this;
    }

    public function removeTransfertFond(TransfertFond $transfertFond): static
    {
        if ($this->transfertFonds->removeElement($transfertFond)) {
            // set the owning side to null (unless already changed)
            if ($transfertFond->getCaisseDepart() === $this) {
                $transfertFond->setCaisseDepart(null);
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
            $facturation->setCaisse($this);
        }

        return $this;
    }

    public function removeFacturation(Facturation $facturation): static
    {
        if ($this->facturations->removeElement($facturation)) {
            // set the owning side to null (unless already changed)
            if ($facturation->getCaisse() === $this) {
                $facturation->setCaisse(null);
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
            $versement->setCompte($this);
        }

        return $this;
    }

    public function removeVersement(Versement $versement): static
    {
        if ($this->versements->removeElement($versement)) {
            // set the owning side to null (unless already changed)
            if ($versement->getCompte() === $this) {
                $versement->setCompte(null);
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
            $modificationFacture->setCaisse($this);
        }

        return $this;
    }

    public function removeModificationFacture(ModificationFacture $modificationFacture): static
    {
        if ($this->modificationFactures->removeElement($modificationFacture)) {
            // set the owning side to null (unless already changed)
            if ($modificationFacture->getCaisse() === $this) {
                $modificationFacture->setCaisse(null);
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
            $suppressionFacture->setCaisse($this);
        }

        return $this;
    }

    public function removeSuppressionFacture(SuppressionFacture $suppressionFacture): static
    {
        if ($this->suppressionFactures->removeElement($suppressionFacture)) {
            // set the owning side to null (unless already changed)
            if ($suppressionFacture->getCaisse() === $this) {
                $suppressionFacture->setCaisse(null);
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
            $clotureCaiss->setCaisse($this);
        }

        return $this;
    }

    public function removeClotureCaiss(ClotureCaisse $clotureCaiss): static
    {
        if ($this->clotureCaisses->removeElement($clotureCaiss)) {
            // set the owning side to null (unless already changed)
            if ($clotureCaiss->getCaisse() === $this) {
                $clotureCaiss->setCaisse(null);
            }
        }

        return $this;
    }
}
