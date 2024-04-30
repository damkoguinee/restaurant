<?php

namespace App\Entity;

use App\Repository\EntreeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntreeRepository::class)]
class Entree extends Product
{
    #[ORM\Column(length: 6)]
    private ?string $etat = null;

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }
}
