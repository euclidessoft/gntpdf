<?php

namespace App\Service;

use App\Entity\Employe;
use App\Entity\HeureSuplementaire;
use App\Entity\Paie;
use App\Entity\PrimePerformance;
use App\Entity\Mois;
use App\Entity\Prime;
use App\Entity\Sanction;
use App\Entity\Calendrier;
use App\Entity\Accompte;
use App\Repository\HeureSuplementaireRepository;
use App\Repository\PaieRepository;
use App\Repository\PrimeRepository;
use App\Repository\SanctionRepository;
use Doctrine\ORM\EntityManagerInterface;

class PaieService
{
    private $paieRepository;
    private $primeRepository;
    private $heureSupRepository;
    private $sanctionRepository;
    private $entityManager;

    public function __construct(PaieRepository $paieRepository,
                                PrimeRepository $primeRepository,
                                HeureSuplementaireRepository $heureSupRepository,
                                SanctionRepository $sanctionRepository,
                                EntityManagerInterface $entityManager)
    {
        $this->paieRepository = $paieRepository;
        $this->primeRepository = $primeRepository;
        $this->heureSupRepository = $heureSupRepository;
        $this->sanctionRepository = $sanctionRepository;
        $this->entityManager = $entityManager;
    }

    public function bulletin(): array
    {
        $entityManager = $this->entityManager;
        // Calcul du début et de la fin du mois
        $startOfMonth = new \DateTime('first day of this month');
        $endOfMonth = new \DateTime('last day of this month');
        $employes = $entityManager->getRepository(Employe::class)->findBy(['status' => true]);
        $mois = $entityManager->getRepository(Mois::class)->find(date('m'));
        $bulletins = [];
        $now = new \DateTime();

        foreach ($employes as $employe) {
            // Vérification si la paie existe déjà pour le mois en cours
            $paieExistante = $this->paieRepository->findByDate($employe->getId(), $startOfMonth, $endOfMonth);
            if ($paieExistante) {
                continue;
            }

            $primes = $entityManager->getRepository(Prime::class)->findBy(['employe' => $employe->getId()]);

            $heureSups = $this->heureSupRepository->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
            $nombreHeures = 0;
            foreach ($heureSups as $heureSup) {
                // calcul nombre d'heure
                $nombreHeures = $nombreHeures + $heureSup->getDuree();
            }


            $primeperformances = $entityManager->getRepository(PrimePerformance::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);
            $totalPrimePerf = 0;
            foreach ($primeperformances as $primeP) {
                // calcul nombre d'heure
                $totalPrimePerf = $totalPrimePerf + $primeP->getMontant();
            }


            $sanctions = $entityManager->getRepository(Sanction::class)->findByDateRange($employe->getId(), $startOfMonth, $endOfMonth);

            $retenues = [];
            $nombreJours = 0;
            $salaireJournalier = $employe->getPoste()->getSalaire() / 30; // Salaire journalier
            $montantRetenue = 0;
            foreach ($sanctions as $sanction) {
                // calcul nombre jours

                if (strtolower($sanction->getTypeSanction()) === 'ponction salariale') {
                    $nombreJours =  $nombreJours + $sanction->getNombreJours();
//                    $montantRetenue = $montantRetenue + $salaireJournalier * $nombreJours;
                } elseif (strtolower($sanction->getTypeSanction()) === 'mis à pied' || strtolower($sanction->getTypeSanction()) === 'mis a pied') {
                    $dateDebut = $sanction->getDateDebut();
                    $dateFin = $sanction->getDateFin();
                    $nombreJours = $nombreJours + $dateDebut->diff($dateFin)->days + 1;
//                    $montantRetenue = $montantRetenue + $salaireJournalier * $nombreJours;
                }

            }

            $accompte= $entityManager->getRepository(Accompte::class)->findOneBy(['employe' => $employe->getId(), 'paye' => false, 'verser' => true], ['id' =>'DESC']);
            
            $totalAccompte= 0;
            if(!empty($accompte)){
                $totalAccompte = $accompte->getMontant();
                
            }
           
            $conge = $entityManager->getRepository(Calendrier::class)->findoneBy(['employe' => $employe->getId()],['id' =>'DESC']);
            $interval = $now->diff($employe->getHireDate());

            $yearDiff = $interval->y;
            $monthDiff = $interval->m + 1; // +1 comme dans votre code original


            $bulletins[] = [
                'employe' => $employe,
                'primes' => $primes,
                'nombreJours' => $nombreJours,
                'totalPrimePerf' => $totalPrimePerf,
                'heureSups' => $nombreHeures,
                'mois' => $mois,
                'yearDiff' => $yearDiff,
                'monthDiff' => $monthDiff,
                'conge' => $conge,
                'accompte' => $totalAccompte,
            ];
        }

        return $bulletins;
    }

    /**
     * Calcul du montant de la retenue pour une sanction
     */

    public function calculeRetenue(Sanction $sanction, float $salaireJournaliere)
    {
        $montantRetenue = 0;
        $retenues = [];
        if ($sanction->getTypeSanction()->getNom() === 'ponction salarial') {
            $nombreJours = $sanction->getNombreJours();
            $montantRetenue = $salaireJournaliere * $nombreJours;
        } elseif ($sanction->getTypeSanction()->getNom() === 'mis a pied') {
            $dateDebut = $sanction->getDateDebut();
            $dateFin = $sanction->getDateFin();
            $nombreJours = $dateDebut->diff($dateFin)->days + 1;
            $montantRetenue = $salaireJournaliere * $nombreJours;
        }

        $retenues[] = [
            'type' => $sanction->getTypeSanction()->getNom(),
            'montantRetenue' => round($montantRetenue, 2),
            'details' => isset($nombreJours) ? "{$nombreJours} jours" : 'Période inconnue',
        ];

        return $retenues;
    }

}
