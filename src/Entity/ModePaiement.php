<?php

namespace App\Entity;

use App\Repository\ModePaiementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModePaiementRepository::class)]
class ModePaiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $designation = null;

    #[ORM\OneToMany(targetEntity: Decaissement::class, mappedBy: 'modePaie')]
    private Collection $decaissements;

    #[ORM\OneToMany(targetEntity: Facturation::class, mappedBy: 'modePayment')]
    private Collection $facturations;

    #[ORM\OneToMany(targetEntity: MouvementCaisse::class, mappedBy: 'modePaie')]
    private Collection $mouvementCaisses;

    #[ORM\OneToMany(targetEntity: Depense::class, mappedBy: 'modePaiement')]
    private Collection $depenses;

    #[ORM\OneToMany(targetEntity: AvanceSalaire::class, mappedBy: 'modePaiement')]
    private Collection $avanceSalaires;

    #[ORM\OneToMany(targetEntity: PaiementSalairePersonnel::class, mappedBy: 'typePaiement')]
    private Collection $paiementSalairePersonnels;

    #[ORM\OneToMany(targetEntity: Versement::class, mappedBy: 'modePaie')]
    private Collection $versements;

    #[ORM\OneToMany(targetEntity: ModificationFacture::class, mappedBy: 'modePaie')]
    private Collection $modificationFactures;

    #[ORM\OneToMany(targetEntity: SuppressionFacture::class, mappedBy: 'modePaie')]
    private Collection $suppressionFactures;

    public function __construct()
    {
        $this->decaissements = new ArrayCollection();
        $this->facturations = new ArrayCollection();
        $this->mouvementCaisses = new ArrayCollection();
        $this->depenses = new ArrayCollection();
        $this->avanceSalaires = new ArrayCollection();
        $this->paiementSalairePersonnels = new ArrayCollection();
        $this->versements = new ArrayCollection();
        $this->modificationFactures = new ArrayCollection();
        $this->suppressionFactures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $decaissement->setModePaie($this);
        }

        return $this;
    }

    public function removeDecaissement(Decaissement $decaissement): static
    {
        if ($this->decaissements->removeElement($decaissement)) {
            // set the owning side to null (unless already changed)
            if ($decaissement->getModePaie() === $this) {
                $decaissement->setModePaie(null);
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
            $mouvementCaiss->setModePaie($this);
        }

        return $this;
    }

    public function removeMouvementCaiss(MouvementCaisse $mouvementCaiss): static
    {
        if ($this->mouvementCaisses->removeElement($mouvementCaiss)) {
            // set the owning side to null (unless already changed)
            if ($mouvementCaiss->getModePaie() === $this) {
                $mouvementCaiss->setModePaie(null);
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
            $depense->setModePaiement($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): static
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getModePaiement() === $this) {
                $depense->setModePaiement(null);
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
            $avanceSalaire->setModePaiement($this);
        }

        return $this;
    }

    public function removeAvanceSalaire(AvanceSalaire $avanceSalaire): static
    {
        if ($this->avanceSalaires->removeElement($avanceSalaire)) {
            // set the owning side to null (unless already changed)
            if ($avanceSalaire->getModePaiement() === $this) {
                $avanceSalaire->setModePaiement(null);
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
            $paiementSalairePersonnel->setTypePaiement($this);
        }

        return $this;
    }

    public function removePaiementSalairePersonnel(PaiementSalairePersonnel $paiementSalairePersonnel): static
    {
        if ($this->paiementSalairePersonnels->removeElement($paiementSalairePersonnel)) {
            // set the owning side to null (unless already changed)
            if ($paiementSalairePersonnel->getTypePaiement() === $this) {
                $paiementSalairePersonnel->setTypePaiement(null);
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
            $versement->setModePaie($this);
        }

        return $this;
    }

    public function removeVersement(Versement $versement): static
    {
        if ($this->versements->removeElement($versement)) {
            // set the owning side to null (unless already changed)
            if ($versement->getModePaie() === $this) {
                $versement->setModePaie(null);
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
            $modificationFacture->setModePaie($this);
        }

        return $this;
    }

    public function removeModificationFacture(ModificationFacture $modificationFacture): static
    {
        if ($this->modificationFactures->removeElement($modificationFacture)) {
            // set the owning side to null (unless already changed)
            if ($modificationFacture->getModePaie() === $this) {
                $modificationFacture->setModePaie(null);
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

    public function addSuppressionFactures(SuppressionFacture $suppressionFactures): static
    {
        if (!$this->suppressionFactures->contains($suppressionFactures)) {
            $this->suppressionFactures->add($suppressionFactures);
            $suppressionFactures->setModePaie($this);
        }

        return $this;
    }

    public function removeSuppressionFactures(SuppressionFacture $suppressionFactures): static
    {
        if ($this->suppressionFactures->removeElement($suppressionFactures)) {
            // set the owning side to null (unless already changed)
            if ($suppressionFactures->getModePaie() === $this) {
                $suppressionFactures->setModePaie(null);
            }
        }

        return $this;
    }
}
