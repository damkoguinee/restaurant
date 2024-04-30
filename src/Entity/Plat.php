<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
class Plat extends Product
{
    #[ORM\Column(length: 6)]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'plats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypePlat $typePlat = null;

    #[ORM\OneToMany(targetEntity: PlatRecette::class, mappedBy: 'plat')]
    private Collection $platRecettes;

    public function __construct()
    {
        $this->platRecettes = new ArrayCollection();
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

    public function getTypePlat(): ?TypePlat
    {
        return $this->typePlat;
    }

    public function setTypePlat(?TypePlat $typePlat): static
    {
        $this->typePlat = $typePlat;

        return $this;
    }

    /**
     * @return Collection<int, PlatRecette>
     */
    public function getPlatRecettes(): Collection
    {
        return $this->platRecettes;
    }

    public function addPlatRecette(PlatRecette $platRecette): static
    {
        if (!$this->platRecettes->contains($platRecette)) {
            $this->platRecettes->add($platRecette);
            $platRecette->setPlat($this);
        }

        return $this;
    }

    public function removePlatRecette(PlatRecette $platRecette): static
    {
        if ($this->platRecettes->removeElement($platRecette)) {
            // set the owning side to null (unless already changed)
            if ($platRecette->getPlat() === $this) {
                $platRecette->setPlat(null);
            }
        }

        return $this;
    }
}
