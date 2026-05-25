<?php

namespace App\Entity;

use App\Repository\PrimeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass:PrimeRepository::class) ]
class Prime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity:Employe::class, inversedBy:"primes") ]
    #[Assert\NotBlank(message:"employe obligatoire")]
    private $employe;

    #[ORM\Column(type:"integer") ]
    #[Assert\NotBlank(message:"montant obligatoire")]
    private $montant;

    #[ORM\Column(type:"string", length:255, nullable:true) ]
    #[Assert\NotBlank(message:"Description obligatoire")]
    private $description;


    #[ORM\Column(type:"datetime") ]
    private $createdAt;

    // #[ORM\Column(type:"boolean") ]
    // private $base;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    // public function getBase(): ?bool
    // {
    //     return $this->base;
    // }

    // public function setBase(bool $base): self
    // {
    //     $this->base = $base;

    //     return $this;
    // }
}
