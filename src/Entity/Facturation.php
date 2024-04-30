<?php

namespace App\Entity;

use App\Repository\FacturationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FacturationRepository::class)]
class Facturation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $numeroFacture = null;

    #[ORM\Column(length: 50)]
    private ?string $numeroTicket = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 13, scale: 2, nullable: true)]
    private ?string $montantTotal = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 13, scale: 2, nullable: true)]
    private ?string $montantPaye = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 13, scale: 2)]
    private ?string $fraisSup = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 13, scale: 2, nullable: true)]
    private ?string $remise = null;

    #[ORM\ManyToOne(inversedBy: 'facturations')]
    private ?User $client = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $tableCommande = null;

    #[ORM\Column(length: 50)]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'facturations')]
    private ?ModePaiement $modePaie = null;

    #[ORM\Column(length: 50)]
    private ?string $typeVente = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFacturation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateSaisie = null;

    #[ORM\ManyToOne(inversedBy: 'facturations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $saisiePar = null;

    #[ORM\Column(length: 15, nullable:true)]
    private ?string $livraison = null;

    #[ORM\ManyToOne(inversedBy: 'facturations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LieuxVentes $lieuVente = null;

    #[ORM\OneToMany(targetEntity: MouvementCaisse::class, mappedBy: 'facturation', orphanRemoval:true, cascade:['persist', 'remove'])]
    private Collection $mouvementCaisses;

    #[ORM\OneToMany(targetEntity: MouvementCollaborateur::class, mappedBy: 'facture', orphanRemoval:true, cascade:['persist', 'remove'])]
    private Collection $mouvementCollaborateurs;

    #[ORM\ManyToOne(inversedBy: 'facturations')]
    private ?Caisse $caisse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateReglement = null;

    #[ORM\OneToMany(targetEntity: CommandeProduct::class, mappedBy: 'facturation', orphanRemoval:true, cascade:['persist', 'remove'])]
    private Collection $commandeProducts;

    #[ORM\OneToMany(targetEntity: ModificationFacture::class, mappedBy: 'facture', orphanRemoval:true, cascade:['persist', 'remove'])]
    private Collection $modificationFactures;

    public function __construct()
    {
        $this->mouvementCaisses = new ArrayCollection();
        $this->mouvementCollaborateurs = new ArrayCollection();
        $this->commandeProducts = new ArrayCollection();
        $this->modificationFactures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroFacture(): ?string
    {
        return $this->numeroFacture;
    }

    public function setNumeroFacture(string $numeroFacture): static
    {
        $this->numeroFacture = $numeroFacture;

        return $this;
    }

    public function getNumeroTicket(): ?string
    {
        return $this->numeroTicket;
    }

    public function setNumeroTicket(string $numeroTicket): static
    {
        $this->numeroTicket = $numeroTicket;

        return $this;
    }

    public function getMontantTotal(): ?string
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(?string $montantTotal): static
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    public function getMontantPaye(): ?string
    {
        return $this->montantPaye;
    }

    public function setMontantPaye(?string $montantPaye): static
    {
        $this->montantPaye = $montantPaye;

        return $this;
    }

    public function getFraisSup(): ?string
    {
        return $this->fraisSup;
    }

    public function setFraisSup(string $fraisSup): static
    {
        $this->fraisSup = $fraisSup;

        return $this;
    }

    public function getRemise(): ?string
    {
        return $this->remise;
    }

    public function setRemise(?string $remise): static
    {
        $this->remise = $remise;

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

    public function getTableCommande(): ?string
    {
        return $this->tableCommande;
    }

    public function setTableCommande(?string $tableCommande): static
    {
        $this->tableCommande = $tableCommande;

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

    public function getModePaie(): ?ModePaiement
    {
        return $this->modePaie;
    }

    public function setModePaie(?ModePaiement $modePaie): static
    {
        $this->modePaie = $modePaie;

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

    public function getDateFacturation(): ?\DateTimeInterface
    {
        return $this->dateFacturation;
    }

    public function setDateFacturation(\DateTimeInterface $dateFacturation): static
    {
        $this->dateFacturation = $dateFacturation;

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

    public function getSaisiePar(): ?User
    {
        return $this->saisiePar;
    }

    public function setSaisiePar(?User $saisiePar): static
    {
        $this->saisiePar = $saisiePar;

        return $this;
    }

    public function getLivraison(): ?string
    {
        return $this->livraison;
    }

    public function setLivraison(string $livraison): static
    {
        $this->livraison = $livraison;

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
            $mouvementCaiss->setFacturation($this);
        }

        return $this;
    }

    public function removeMouvementCaiss(MouvementCaisse $mouvementCaiss): static
    {
        if ($this->mouvementCaisses->removeElement($mouvementCaiss)) {
            // set the owning side to null (unless already changed)
            if ($mouvementCaiss->getFacturation() === $this) {
                $mouvementCaiss->setFacturation(null);
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
            $mouvementCollaborateur->setFacture($this);
        }

        return $this;
    }

    public function removeMouvementCollaborateur(MouvementCollaborateur $mouvementCollaborateur): static
    {
        if ($this->mouvementCollaborateurs->removeElement($mouvementCollaborateur)) {
            // set the owning side to null (unless already changed)
            if ($mouvementCollaborateur->getFacture() === $this) {
                $mouvementCollaborateur->setFacture(null);
            }
        }

        return $this;
    }

    public function getCaisse(): ?Caisse
    {
        return $this->caisse;
    }

    public function setCaisse(?Caisse $caisse): static
    {
        $this->caisse = $caisse;

        return $this;
    }

    public function getDateReglement(): ?\DateTimeInterface
    {
        return $this->dateReglement;
    }

    public function setDateReglement(?\DateTimeInterface $dateReglement): static
    {
        $this->dateReglement = $dateReglement;

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
            $commandeProduct->setFacturation($this);
        }

        return $this;
    }

    public function removeCommandeProduct(CommandeProduct $commandeProduct): static
    {
        if ($this->commandeProducts->removeElement($commandeProduct)) {
            // set the owning side to null (unless already changed)
            if ($commandeProduct->getFacturation() === $this) {
                $commandeProduct->setFacturation(null);
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
            $modificationFacture->setFacture($this);
        }

        return $this;
    }

    public function removeModificationFacture(ModificationFacture $modificationFacture): static
    {
        if ($this->modificationFactures->removeElement($modificationFacture)) {
            // set the owning side to null (unless already changed)
            if ($modificationFacture->getFacture() === $this) {
                $modificationFacture->setFacture(null);
            }
        }

        return $this;
    }
}
