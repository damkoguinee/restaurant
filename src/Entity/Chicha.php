<?php

namespace App\Entity;

use App\Repository\ChichaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChichaRepository::class)]
class Chicha extends Product
{
    #[ORM\Column(length: 6)]
    private ?string $etat = null;

    #[ORM\OneToMany(targetEntity: ChichaRecette::class, mappedBy: 'chicha')]
    private Collection $chichaRecettes;

    public function __construct()
    {
        $this->chichaRecettes = new ArrayCollection();
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
     * @return Collection<int, ChichaRecette>
     */
    public function getChichaRecettes(): Collection
    {
        return $this->chichaRecettes;
    }

    public function addChichaRecette(ChichaRecette $chichaRecette): static
    {
        if (!$this->chichaRecettes->contains($chichaRecette)) {
            $this->chichaRecettes->add($chichaRecette);
            $chichaRecette->setChicha($this);
        }

        return $this;
    }

    public function removeChichaRecette(ChichaRecette $chichaRecette): static
    {
        if ($this->chichaRecettes->removeElement($chichaRecette)) {
            // set the owning side to null (unless already changed)
            if ($chichaRecette->getChicha() === $this) {
                $chichaRecette->setChicha(null);
            }
        }

        return $this;
    }
}
