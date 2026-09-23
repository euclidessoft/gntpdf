<?php

namespace App\Entity;

use App\Repository\AvantageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvantageRepository::class)]
class Avantage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?AveCom $AveCom = null;


    #[ORM\Column]
    private ?float $ca = null;
    
    #[ORM\Column]
    private ?float $ristourne = null;

    #[ORM\Column]
    private ?float $commission = null;

    #[ORM\Column]
    private ?float $tva = null;

    #[ORM\Column]
    private ?float $achat = null;

    #[ORM\Column]
    private ?float $escompte = null;

    
    #[ORM\Column(type:"boolean") ]
    private $payer;

    #[ORM\ManyToOne]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'avantages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employe $employe = null;

    #[ORM\Column(type:"float") ]
    private $Montanttraiter;
   
    #[ORM\OneToMany(
        mappedBy: 'avantage',
        targetEntity: CommandeAvantage::class,
        cascade: ['persist', 'remove']
    )]
    private Collection $commandeavantages;

    #[ORM\Column(type:"float") ]
    private $Montant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->payer = false;
        $this->commandeavantages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRistourne(): ?float
    {
        return $this->ristourne;
    }

    public function setRistourne(float $ristourne): static
    {
        $this->ristourne = $ristourne;

        return $this;
    }

    public function getCommission(): ?float
    {
        return $this->commission;
    }

    public function setCommission(float $commission): static
    {
        $this->commission = $commission;

        return $this;
    }

    public function getEscompte(): ?float
    {
        return $this->escompte;
    }

    public function setEscompte(float $escompte): static
    {
        $this->escompte = $escompte;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): static
    {
        $this->employe = $employe;

        return $this;
    }

    public function getCa(): ?float
    {
        return $this->ca;
    }

    public function setCa(float $ca): static
    {
        $this->ca = $ca;

        return $this;
    }

    public function getAchat(): ?float
    {
        return $this->achat;
    }

    public function setAchat(float $achat): static
    {
        $this->achat = $achat;

        return $this;
    }

    public function getAveCom(): ?AveCom
    {
        return $this->AveCom;
    }

    public function setAveCom(?AveCom $AveCom): static
    {
        $this->AveCom = $AveCom;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function isPayer(): ?bool
    {
        return $this->payer;
    }

    public function setPayer(bool $payer): static
    {
        $this->payer = $payer;

        return $this;
    }

    public function getMontanttraiter(): ?float
    {
        return $this->Montanttraiter;
    }

    public function setMontanttraiter(float $Montanttraiter): static
    {
        $this->Montanttraiter = $Montanttraiter;

        return $this;
    }

    /**
     * @return Collection<int, CommandeAvantage>
     */
    public function getCommandeavantages(): Collection
    {
        return $this->commandeavantages;
    }

    public function addCommandeavantage(CommandeAvantage $commandeavantage): static
    {
        if (!$this->commandeavantages->contains($commandeavantage)) {
            $this->commandeavantages->add($commandeavantage);
            $commandeavantage->setAvantage($this);
        }

        return $this;
    }

    public function removeCommandeavantage(CommandeAvantage $commandeavantage): static
    {
        if ($this->commandeavantages->removeElement($commandeavantage)) {
            // set the owning side to null (unless already changed)
            if ($commandeavantage->getAvantage() === $this) {
                $commandeavantage->setAvantage(null);
            }
        }

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->Montant;
    }

    public function setMontant(float $Montant): static
    {
        $this->Montant = $Montant;

        return $this;
    }
}
