<?php

namespace App\Entity;

use App\Repository\SanctionRepository;
use Doctrine\ORM\Mapping as ORM;
use symfony\component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass:SanctionRepository::class) ]
class Sanction
{
    const type = [

        'Ponction Salariale' => 'Ponction Salariale',
        'Mis à pied' => 'Mis à pied' ,
        'Retenue sur les congés' => 'Retenue sur les congés' ,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:"datetime") ]
    private $dateCreation;

    // #[ORM\ManyToOne(targetEntity:TypeSanction::class, inversedBy:"sanctions") ]
    // #[Assert\NotBlank(message: "Champ obligatoire") ]
    // private $typeSanction;

    #[ORM\Column(type:"string", length:255) ]
    #[Assert\NotBlank(message: "Champ obligatoire") ]
    private $typeSanction;

    #[ORM\ManyToOne(targetEntity:Employe::class, inversedBy:"sanctions") ]
     #[Assert\NotBlank(message: "Champ obligatoire") ]
    private $employe;

    #[ORM\Column(type:"datetime", nullable:true) ]
    private $dateDebut;

    #[ORM\Column(type:"datetime", nullable:true) ]
    private $dateFin;

    #[ORM\Column(type:"integer", nullable:true) ]
    private $nombreJours;

    #[ORM\Column(type:"datetime") ]
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }


    // public function getTypeSanction(): ?TypeSanction
    // {
    //     return $this->typeSanction;
    // }

    // public function setTypeSanction(?TypeSanction $typeSanction): self
    // {
    //     $this->typeSanction = $typeSanction;

    //     return $this;
    // }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getNombreJours(): ?int
    {
        return $this->nombreJours;
    }

    public function setNombreJours(?int $nombreJours): self
    {
        $this->nombreJours = $nombreJours;

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


    public function getTypeSanction(): ?string
    {
        return $this->typeSanction;
    }

    public function setTypeSanction(string $typeSanction): static
    {
        $this->typeSanction = $typeSanction;

        return $this;
    }
}
