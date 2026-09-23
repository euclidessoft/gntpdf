<?php

namespace App\Entity;

use App\Repository\CommandeAvantageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeAvantageRepository::class)]
class CommandeAvantage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

     #[ORM\ManyToOne(inversedBy: 'commandeavantages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commandeavant = null;

    #[ORM\ManyToOne(inversedBy: 'commandeavantages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Avantage $avantage = null;

    #[ORM\Column]
    private ?float $montant = null;

    
    #[ORM\Column(type:"datetime") ]
    private $date;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \Datetime();
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCommandeavant(): ?Commande
    {
        return $this->commandeavant;
    }

    public function setCommandeavant(?Commande $commandeavant): static
    {
        $this->commandeavant = $commandeavant;

        return $this;
    }

    public function getAvantage(): ?Avantage
    {
        return $this->avantage;
    }

    public function setAvantage(?Avantage $avantage): static
    {
        $this->avantage = $avantage;

        return $this;
    }
}
