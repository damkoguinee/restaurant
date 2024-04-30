<?php

namespace App\Entity;

use App\Repository\CategorieBoissonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieBoissonRepository::class)]
class CategorieBoisson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?bool $alcoolise = null;

    #[ORM\OneToMany(targetEntity: Boisson::class, mappedBy: 'categorie')]
    private Collection $boissons;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->boissons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isAlcoolise(): ?bool
    {
        return $this->alcoolise;
    }

    public function setAlcoolise(bool $alcoolise): static
    {
        $this->alcoolise = $alcoolise;

        return $this;
    }

    /**
     * @return Collection<int, Boisson>
     */
    public function getBoissons(): Collection
    {
        return $this->boissons;
    }

    public function addBoisson(Boisson $boisson): static
    {
        if (!$this->boissons->contains($boisson)) {
            $this->boissons->add($boisson);
            $boisson->setCategorie($this);
        }

        return $this;
    }

    public function removeBoisson(Boisson $boisson): static
    {
        if ($this->boissons->removeElement($boisson)) {
            // set the owning side to null (unless already changed)
            if ($boisson->getCategorie() === $this) {
                $boisson->setCategorie(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    
}
