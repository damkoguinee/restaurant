<?php 
namespace App\Entity;

interface ProductInterface {
    public function getId(): ?int;
    public function getNom(): ?string;
    public function getDescription(): ?string;
    public function getPrixVente(): ?float;
    public function getImage(): ?string;
    public function getService(): ?Service;
    // public function getLieuVente(): ?LieuxVentes;
}