<?php

namespace App\Entity;

use App\Repository\PaieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:PaieRepository::class) ]
class Paie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity:Employe::class, inversedBy:"paies") ]
    private $employe;

    #[ORM\Column(type:"date") ]
    private $date;

    #[ORM\Column(type:"float") ]
    private $salaireBase;

    #[ORM\Column(type:"float") ]
    private $code;

    #[ORM\Column(type:"float") ]
    private $jours;
   
    #[ORM\Column(type:"float") ]
    private $baseenciennete;

    #[ORM\Column(type:"float") ]
    private $tauxenciennete;
    
    #[ORM\Column(type:"text", nullable:true) ] 
    protected $indemnite;
    
    #[ORM\Column(type:"float", nullable:true) ] 
    protected $performance;

    #[ORM\Column(type:"float") ]
    private $baseheuresup;

    #[ORM\Column(type:"float") ]
    private $tauxheuresup;

    #[ORM\Column(type:"float") ]
    private $baseponction;

    #[ORM\Column(type:"float") ]
    private $tauxponction;

    #[ORM\Column(type:"float", nullable:true) ]
    private $Brut;

    #[ORM\Column(type:"float", nullable:true) ]
    private $Brutinter;

    #[ORM\Column(type:"float", nullable:true) ]
    private $Bruttaxable;

    #[ORM\Column(type:"float", nullable:true) ]
    private $logementfisc;

    #[ORM\Column(type:"float", nullable:true) ]
    private $vehiculefisc;

    #[ORM\Column(type:"float", nullable:true) ]
    private $logementcnps;

    #[ORM\Column(type:"float", nullable:true) ]
    private $vehiculecnps;

    #[ORM\Column(type:"float", nullable:true) ]
    private $salairecotisable;

    #[ORM\Column(type:"float", nullable:true) ]
    private $baseirpp;

    #[ORM\Column(type:"float", nullable:true) ]
    private $tauxirpp;

    #[ORM\Column(type:"float", nullable:true) ]
    private $irpp;

    #[ORM\Column(type:"float", nullable:true) ]
    private $baseca;

    #[ORM\Column(type:"float", nullable:true) ]
    private $tauxca;

    #[ORM\Column(type:"float", nullable:true) ]
    private $ca;

    #[ORM\Column(type:"float", nullable:true) ]
    private $baselocal;

    #[ORM\Column(type:"float", nullable:true) ]
    private $tauxlocal;

    #[ORM\Column(type:"float", nullable:true) ]
    private $local;

    #[ORM\Column(type:"float", nullable:true) ]
    private $basevieil;

    #[ORM\Column(type:"float", nullable:true) ]
    private $tauxvieil;

    #[ORM\Column(type:"float", nullable:true) ]
    private $vieil;

    #[ORM\Column(type:"float", nullable:true) ]
    private $basefoncier;

    #[ORM\Column(type:"float", nullable:true) ]
    private $tauxfoncier;

    #[ORM\Column(type:"float", nullable:true) ]
    private $foncier;

    #[ORM\Column(type:"float", nullable:true) ]
    private $basecrtv;

    #[ORM\Column(type:"float", nullable:true) ]
    private $tauxcrtv;

    #[ORM\Column(type:"float", nullable:true) ]
    private $crtv;

    #[ORM\Column(type:"float", nullable:true) ]
    private $allocation;

    #[ORM\Column(type:"float", nullable:true) ]
    private $cpvieil;

    #[ORM\Column(type:"float", nullable:true) ]
    private $tav;

    #[ORM\Column(type:"float", nullable:true) ]
    private $cpfoncier;

    #[ORM\Column(type:"float", nullable:true) ]
    private $fne;

    #[ORM\Column(type:"float") ]
    private $salaireNet;

    #[ORM\Column(type:"float", nullable:true) ]
    private $accompte;

    #[ORM\Column(type:"float", nullable:true) ]
    private $pret;

    #[ORM\Column(type:"float", nullable:true) ]
    private $autres;

    #[ORM\ManyToOne(targetEntity:Mois::class, inversedBy:"paies") ]
    private $mois;

    #[ORM\Column(type:"boolean") ]
    private $payer;

    #[ORM\Column(type:"date", nullable:true) ]
    private $datepaye;

     #[ORM\Column(type:"date", nullable:true) ]
    private $debutConge;

    #[ORM\Column(type:"date", nullable:true) ]
    private $finConge;

    #[ORM\Column(length: 255)]
    private ?string $anciennete = null;

    #[ORM\Column(length: 255)]
    private ?string $categorie = null;

    #[ORM\Column(length: 255)]
    private ?string $echelle = null;

    #[ORM\Column(length: 255)]
    private ?string $cnps = null;

    #[ORM\Column(length: 255)]
    private ?string $banque = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalchargepatronal = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalChargeEmploye = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fonction = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $departement = null;

    #[ORM\Column(length: 255)]
    private ?string $codeanciennete = null;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->payer = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSalaireBase(): ?float
    {
        return $this->salaireBase;
    }

    public function setSalaireBase(float $salaireBase): static
    {
        $this->salaireBase = $salaireBase;

        return $this;
    }

    public function getCode(): ?float
    {
        return $this->code;
    }

    public function setCode(float $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getJours(): ?float
    {
        return $this->jours;
    }

    public function setJours(float $jours): static
    {
        $this->jours = $jours;

        return $this;
    }

    public function getBaseenciennete(): ?float
    {
        return $this->baseenciennete;
    }

    public function setBaseenciennete(float $baseenciennete): static
    {
        $this->baseenciennete = $baseenciennete;

        return $this;
    }

    public function getTauxenciennete(): ?float
    {
        return $this->tauxenciennete;
    }

    public function setTauxenciennete(float $tauxenciennete): static
    {
        $this->tauxenciennete = $tauxenciennete;

        return $this;
    }

    public function getIndemnite(): ?string
    {
        return $this->indemnite;
    }

    public function setIndemnite(?string $indemnite): static
    {
        $this->indemnite = $indemnite;

        return $this;
    }

    public function getPerformance(): ?float
    {
        return $this->performance;
    }

    public function setPerformance(?float $performance): static
    {
        $this->performance = $performance;

        return $this;
    }

    public function getBaseheuresup(): ?float
    {
        return $this->baseheuresup;
    }

    public function setBaseheuresup(float $baseheuresup): static
    {
        $this->baseheuresup = $baseheuresup;

        return $this;
    }

    public function getTauxheuresup(): ?float
    {
        return $this->tauxheuresup;
    }

    public function setTauxheuresup(float $tauxheuresup): static
    {
        $this->tauxheuresup = $tauxheuresup;

        return $this;
    }

    public function getBaseponction(): ?float
    {
        return $this->baseponction;
    }

    public function setBaseponction(float $baseponction): static
    {
        $this->baseponction = $baseponction;

        return $this;
    }

    public function getTauxponction(): ?float
    {
        return $this->tauxponction;
    }

    public function setTauxponction(float $tauxponction): static
    {
        $this->tauxponction = $tauxponction;

        return $this;
    }

    public function getBrut(): ?float
    {
        return $this->Brut;
    }

    public function setBrut(?float $Brut): static
    {
        $this->Brut = $Brut;

        return $this;
    }

    public function getBrutinter(): ?float
    {
        return $this->Brutinter;
    }

    public function setBrutinter(?float $Brutinter): static
    {
        $this->Brutinter = $Brutinter;

        return $this;
    }

    public function getBruttaxable(): ?float
    {
        return $this->Bruttaxable;
    }

    public function setBruttaxable(?float $Bruttaxable): static
    {
        $this->Bruttaxable = $Bruttaxable;

        return $this;
    }

    public function getLogementfisc(): ?float
    {
        return $this->logementfisc;
    }

    public function setLogementfisc(?float $logementfisc): static
    {
        $this->logementfisc = $logementfisc;

        return $this;
    }

    public function getVehiculefisc(): ?float
    {
        return $this->vehiculefisc;
    }

    public function setVehiculefisc(?float $vehiculefisc): static
    {
        $this->vehiculefisc = $vehiculefisc;

        return $this;
    }

    public function getLogementcnps(): ?float
    {
        return $this->logementcnps;
    }

    public function setLogementcnps(?float $logementcnps): static
    {
        $this->logementcnps = $logementcnps;

        return $this;
    }

    public function getVehiculecnps(): ?float
    {
        return $this->vehiculecnps;
    }

    public function setVehiculecnps(?float $vehiculecnps): static
    {
        $this->vehiculecnps = $vehiculecnps;

        return $this;
    }

    public function getSalairecotisable(): ?float
    {
        return $this->salairecotisable;
    }

    public function setSalairecotisable(?float $salairecotisable): static
    {
        $this->salairecotisable = $salairecotisable;

        return $this;
    }

    public function getBaseirpp(): ?float
    {
        return $this->baseirpp;
    }

    public function setBaseirpp(?float $baseirpp): static
    {
        $this->baseirpp = $baseirpp;

        return $this;
    }

    public function getTauxirpp(): ?float
    {
        return $this->tauxirpp;
    }

    public function setTauxirpp(?float $tauxirpp): static
    {
        $this->tauxirpp = $tauxirpp;

        return $this;
    }

    public function getIrpp(): ?float
    {
        return $this->irpp;
    }

    public function setIrpp(?float $irpp): static
    {
        $this->irpp = $irpp;

        return $this;
    }

    public function getBaseca(): ?float
    {
        return $this->baseca;
    }

    public function setBaseca(?float $baseca): static
    {
        $this->baseca = $baseca;

        return $this;
    }

    public function getTauxca(): ?float
    {
        return $this->tauxca;
    }

    public function setTauxca(?float $tauxca): static
    {
        $this->tauxca = $tauxca;

        return $this;
    }

    public function getCa(): ?float
    {
        return $this->ca;
    }

    public function setCa(?float $ca): static
    {
        $this->ca = $ca;

        return $this;
    }

    public function getBaselocal(): ?float
    {
        return $this->baselocal;
    }

    public function setBaselocal(?float $baselocal): static
    {
        $this->baselocal = $baselocal;

        return $this;
    }

    public function getTauxlocal(): ?float
    {
        return $this->tauxlocal;
    }

    public function setTauxlocal(?float $tauxlocal): static
    {
        $this->tauxlocal = $tauxlocal;

        return $this;
    }

    public function getLocal(): ?float
    {
        return $this->local;
    }

    public function setLocal(?float $local): static
    {
        $this->local = $local;

        return $this;
    }

    public function getBasevieil(): ?float
    {
        return $this->basevieil;
    }

    public function setBasevieil(?float $basevieil): static
    {
        $this->basevieil = $basevieil;

        return $this;
    }

    public function getTauxvieil(): ?float
    {
        return $this->tauxvieil;
    }

    public function setTauxvieil(?float $tauxvieil): static
    {
        $this->tauxvieil = $tauxvieil;

        return $this;
    }

    public function getVieil(): ?float
    {
        return $this->vieil;
    }

    public function setVieil(?float $vieil): static
    {
        $this->vieil = $vieil;

        return $this;
    }

    public function getBasefoncier(): ?float
    {
        return $this->basefoncier;
    }

    public function setBasefoncier(?float $basefoncier): static
    {
        $this->basefoncier = $basefoncier;

        return $this;
    }

    public function getTauxfoncier(): ?float
    {
        return $this->tauxfoncier;
    }

    public function setTauxfoncier(?float $tauxfoncier): static
    {
        $this->tauxfoncier = $tauxfoncier;

        return $this;
    }

    public function getFoncier(): ?float
    {
        return $this->foncier;
    }

    public function setFoncier(?float $foncier): static
    {
        $this->foncier = $foncier;

        return $this;
    }

    public function getBasecrtv(): ?float
    {
        return $this->basecrtv;
    }

    public function setBasecrtv(?float $basecrtv): static
    {
        $this->basecrtv = $basecrtv;

        return $this;
    }

    public function getTauxcrtv(): ?float
    {
        return $this->tauxcrtv;
    }

    public function setTauxcrtv(?float $tauxcrtv): static
    {
        $this->tauxcrtv = $tauxcrtv;

        return $this;
    }

    public function getCrtv(): ?float
    {
        return $this->crtv;
    }

    public function setCrtv(?float $crtv): static
    {
        $this->crtv = $crtv;

        return $this;
    }

    public function getAllocation(): ?float
    {
        return $this->allocation;
    }

    public function setAllocation(?float $allocation): static
    {
        $this->allocation = $allocation;

        return $this;
    }

    public function getCpvieil(): ?float
    {
        return $this->cpvieil;
    }

    public function setCpvieil(?float $cpvieil): static
    {
        $this->cpvieil = $cpvieil;

        return $this;
    }

    public function getTav(): ?float
    {
        return $this->tav;
    }

    public function setTav(?float $tav): static
    {
        $this->tav = $tav;

        return $this;
    }

    public function getCpfoncier(): ?float
    {
        return $this->cpfoncier;
    }

    public function setCpfoncier(?float $cpfoncier): static
    {
        $this->cpfoncier = $cpfoncier;

        return $this;
    }

    public function getFne(): ?float
    {
        return $this->fne;
    }

    public function setFne(?float $fne): static
    {
        $this->fne = $fne;

        return $this;
    }

    public function getSalaireNet(): ?float
    {
        return $this->salaireNet;
    }

    public function setSalaireNet(float $salaireNet): static
    {
        $this->salaireNet = $salaireNet;

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

    public function getDatepaye(): ?\DateTimeInterface
    {
        return $this->datepaye;
    }

    public function setDatepaye(?\DateTimeInterface $datepaye): static
    {
        $this->datepaye = $datepaye;

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

    public function getMois(): ?Mois
    {
        return $this->mois;
    }

    public function setMois(?Mois $mois): static
    {
        $this->mois = $mois;

        return $this;
    }

    public function getAccompte(): ?int
    {
        return $this->accompte;
    }

    public function setAccompte(int $accompte): static
    {
        $this->accompte = $accompte;

        return $this;
    }

    public function getPret(): ?int
    {
        return $this->pret;
    }

    public function setPret(int $pret): static
    {
        $this->pret = $pret;

        return $this;
    }

    public function getAutres(): ?int
    {
        return $this->autres;
    }

    public function setAutres(int $autres): static
    {
        $this->autres = $autres;

        return $this;
    }

    public function getDebutConge(): ?\DateTimeInterface
    {
        return $this->debutConge;
    }

    public function setDebutConge(\DateTimeInterface $debutConge): static
    {
        $this->debutConge = $debutConge;

        return $this;
    }

    public function getFinConge(): ?\DateTimeInterface
    {
        return $this->finConge;
    }

    public function setFinConge(\DateTimeInterface $finConge): static
    {
        $this->finConge = $finConge;

        return $this;
    }

    public function getAnciennete(): ?string
    {
        return $this->anciennete;
    }

    public function setAnciennete(string $anciennete): static
    {
        $this->anciennete = $anciennete;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getEchelle(): ?string
    {
        return $this->echelle;
    }

    public function setEchelle(string $echelle): static
    {
        $this->echelle = $echelle;

        return $this;
    }

    public function getCnps(): ?string
    {
        return $this->cnps;
    }

    public function setCnps(string $cnps): static
    {
        $this->cnps = $cnps;

        return $this;
    }

    public function getBanque(): ?string
    {
        return $this->banque;
    }

    public function setBanque(string $banque): static
    {
        $this->banque = $banque;

        return $this;
    }

    public function getTotalchargepatronal(): ?float
    {
        return $this->totalchargepatronal;
    }

    public function setTotalchargepatronal(?float $totalchargepatronal): static
    {
        $this->totalchargepatronal = $totalchargepatronal;

        return $this;
    }

    public function getTotalChargeEmploye(): ?float
    {
        return $this->totalChargeEmploye;
    }

    public function setTotalChargeEmploye(?float $totalChargeEmploye): static
    {
        $this->totalChargeEmploye = $totalChargeEmploye;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): static
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(?string $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    public function getCodeanciennete(): ?string
    {
        return $this->codeanciennete;
    }

    public function setCodeanciennete(string $codeanciennete): static
    {
        $this->codeanciennete = $codeanciennete;

        return $this;
    }


}
