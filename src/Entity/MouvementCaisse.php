<?php

namespace App\Entity;

use App\Repository\MouvementCaisseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MouvementCaisseRepository::class)]
class MouvementCaisse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LieuxVentes $lieuVente = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Caisse $caisse = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategorieOperation $categorieOperation = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CompteOperation $compteOperation = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devise $devise = null;

    #[ORM\Column(length: 255)]
    private ?string $typeMouvement = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 13, scale: 2)]
    private ?string $montant = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateOperation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateSaisie = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    private ?Decaissement $decaissement = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    private ?Depense $depense = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    private ?Facturation $facturation = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    private ?ModePaiement $modePaie = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $numeroPaie = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $banqueCheque = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    private ?AvanceSalaire $avanceSalaire = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    private ?PaiementSalairePersonnel $paiementSalaire = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    private ?TransfertFond $transfertFond = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    private ?Versement $versement = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $saisiePar = null;

    #[ORM\ManyToOne(inversedBy: 'mouvementCaisses')]
    private ?ClotureCaisse $cloture = null;

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

    public function getCaisse(): ?Caisse
    {
        return $this->caisse;
    }

    public function setCaisse(?Caisse $caisse): static
    {
        $this->caisse = $caisse;

        return $this;
    }

    public function getCategorieOperation(): ?CategorieOperation
    {
        return $this->categorieOperation;
    }

    public function setCategorieOperation(?CategorieOperation $categorieOperation): static
    {
        $this->categorieOperation = $categorieOperation;

        return $this;
    }

    public function getCompteOperation(): ?CompteOperation
    {
        return $this->compteOperation;
    }

    public function setCompteOperation(?CompteOperation $compteOperation): static
    {
        $this->compteOperation = $compteOperation;

        return $this;
    }

    public function getDevise(): ?Devise
    {
        return $this->devise;
    }

    public function setDevise(?Devise $devise): static
    {
        $this->devise = $devise;

        return $this;
    }

    public function getTypeMouvement(): ?string
    {
        return $this->typeMouvement;
    }

    public function setTypeMouvement(string $typeMouvement): static
    {
        $this->typeMouvement = $typeMouvement;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateOperation(): ?\DateTimeInterface
    {
        return $this->dateOperation;
    }

    public function setDateOperation(\DateTimeInterface $dateOperation): static
    {
        $this->dateOperation = $dateOperation;

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

    public function getDecaissement(): ?Decaissement
    {
        return $this->decaissement;
    }

    public function setDecaissement(?Decaissement $decaissement): static
    {
        $this->decaissement = $decaissement;

        return $this;
    }

    public function getDepense(): ?Depense
    {
        return $this->depense;
    }

    public function setDepense(?Depense $depense): static
    {
        $this->depense = $depense;

        return $this;
    }

    public function getFacturation(): ?Facturation
    {
        return $this->facturation;
    }

    public function setFacturation(?Facturation $facturation): static
    {
        $this->facturation = $facturation;

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

    public function getNumeroPaie(): ?string
    {
        return $this->numeroPaie;
    }

    public function setNumeroPaie(?string $numeroPaie): static
    {
        $this->numeroPaie = $numeroPaie;

        return $this;
    }

    public function getBanqueCheque(): ?string
    {
        return $this->banqueCheque;
    }

    public function setBanqueCheque(?string $banqueCheque): static
    {
        $this->banqueCheque = $banqueCheque;

        return $this;
    }

    public function getAvanceSalaire(): ?AvanceSalaire
    {
        return $this->avanceSalaire;
    }

    public function setAvanceSalaire(?AvanceSalaire $avanceSalaire): static
    {
        $this->avanceSalaire = $avanceSalaire;

        return $this;
    }

    public function getPaiementSalaire(): ?PaiementSalairePersonnel
    {
        return $this->paiementSalaire;
    }

    public function setPaiementSalaire(?PaiementSalairePersonnel $paiementSalaire): static
    {
        $this->paiementSalaire = $paiementSalaire;

        return $this;
    }

    public function getTransfertFond(): ?TransfertFond
    {
        return $this->transfertFond;
    }

    public function setTransfertFond(?TransfertFond $transfertFond): static
    {
        $this->transfertFond = $transfertFond;

        return $this;
    }

    public function getVersement(): ?Versement
    {
        return $this->versement;
    }

    public function setVersement(?Versement $versement): static
    {
        $this->versement = $versement;

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

    public function getCloture(): ?ClotureCaisse
    {
        return $this->cloture;
    }

    public function setCloture(?ClotureCaisse $cloture): static
    {
        $this->cloture = $cloture;

        return $this;
    }
}
