<?php

namespace App\Entity;

use App\Repository\AccompteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccompteRepository::class)]
class Accompte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'accomptes')]
    #[Assert\NotBlank(message: "Selectionnez un employé") ]
    private ?Employe $employe = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Montant obligatoire") ]
    private ?float $montant = null;

    #[ORM\Column]
    private ?bool $paye = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateverser = null;

    #[ORM\Column]
    private ?bool $verser = null;


    public function __construct()
    {
        $this->paye = false;
        $this->verser = false;
        $this->date = new \Datetime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function isPaye(): ?bool
    {
        return $this->paye;
    }

    public function setPaye(bool $paye): static
    {
        $this->paye = $paye;

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

    public function getDateverser(): ?\DateTime
    {
        return $this->dateverser;
    }

    public function setDateverser(?\DateTime $dateverser): static
    {
        $this->dateverser = $dateverser;

        return $this;
    }

    public function getVerser(): ?bool
    {
        return $this->verser;
    }

    public function setVerser(bool $verser): static
    {
        $this->verser = $verser;

        return $this;
    }
}
