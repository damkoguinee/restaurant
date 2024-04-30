<?php

namespace App\Entity;

use App\Repository\EcranRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EcranRepository::class)]
class Ecran
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ecrans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    #[ORM\Column(length: 50)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $codeIdentification = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getCodeIdentification(): ?string
    {
        return $this->codeIdentification;
    }

    public function setCodeIdentification(string $codeIdentification): static
    {
        $this->codeIdentification = $codeIdentification;

        return $this;
    }
}
