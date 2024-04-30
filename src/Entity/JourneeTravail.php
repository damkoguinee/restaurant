<?php

namespace App\Entity;

use App\Repository\JourneeTravailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JourneeTravailRepository::class)]
class JourneeTravail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $jourDeTravail = null;

    #[ORM\ManyToOne(inversedBy: 'journeeTravails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LieuxVentes $lieuVente = null;

    #[ORM\Column(length: 10)]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'journeeTravails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $saisiePar = null;

    #[ORM\ManyToOne(inversedBy: 'journeeTravails')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $cloturerPar = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateSaisie = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCloture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJourDeTravail(): ?\DateTimeInterface
    {
        return $this->jourDeTravail;
    }

    public function setJourDeTravail(\DateTimeInterface $jourDeTravail): static
    {
        $this->jourDeTravail = $jourDeTravail;

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

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

    public function getCloturerPar(): ?User
    {
        return $this->cloturerPar;
    }

    public function setCloturerPar(?User $cloturerPar): static
    {
        $this->cloturerPar = $cloturerPar;

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

    public function getDateCloture(): ?\DateTimeInterface
    {
        return $this->dateCloture;
    }

    public function setDateCloture(?\DateTimeInterface $dateCloture): static
    {
        $this->dateCloture = $dateCloture;

        return $this;
    }
}
