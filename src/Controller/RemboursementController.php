<?php

namespace App\Controller;

use App\Complement\Solde;
use App\Entity\Avoir;
use App\Entity\Debit;
use App\Entity\Ecriture;
use App\Entity\Interet;
use App\Entity\Remboursement;
use App\Form\RemboursementType;
use App\Form\RemboursementbancaireType;
use App\Repository\AvoirRepository;
use App\Repository\AvoirResteRepository;
use App\Repository\RemboursementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route("/{_locale}/remboursement") ]
class RemboursementController extends AbstractController
{
    public function __construct(private Security $security, private EntityManagerInterface $entityManager)
    {
    }


#[Route("/", name :"remboursement_index", methods : ["GET"]) ]
    public function index(RemboursementRepository $remboursementRepository): Response
    {
        return $this->render('remboursement/index.html.twig', [
            'remboursements' => $remboursementRepository->findAll(),
        ]);
    }
    #[Route("/Avoir_list", name :"remboursement_avoir_index", methods : ["GET"]) ]
    public function avoir_list(AvoirRepository $avoirRepository, SessionInterface $session): Response
    {
        if ($this->security->isGranted('ROLE_FINANCE')) {
            return $this->render('remboursement/avoir_list.html.twig', [
                'avoirs' => $avoirRepository->findby(['rebourser' => false]),

            ]);
        }  else {
            $response = $this->redirectToRoute('security_logout');
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
    }

    #[Route("/Choix_financement", name :"remboursement_choix", methods : ["GET","POST"]) ]
    public function financementChoix(Request $request): Response
    {
        return $this->render('remboursement/choix_remboursement.html.twig');
    }

    #[Route("/financementApport", name :"remboursement_espece", methods : ["GET","POST"]) ]
    public function financementapport(Request $request, Solde $solde): Response
    {
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->entityManager;

            $remboursement->setCompte($remboursement->getFinancement()->getCompte());

            $debit = new Debit();
            $ecriture = new Ecriture();
            $debit->setRemboursement($remboursement);
            $montant = 0;

            if($remboursement->getType() == 'Espece') {
                $montant = $solde->montantcaisse($entityManager, 571);

                    $remboursement->setType('Espece');

                    $debit->setType('Espece');
                    $debit->setCompte('571');

                    $ecriture->setType('Espece');
                    $ecriture->setComptedebit('571');
                    $ecriture->setLibellecomptedebit("Caisse");
                    $ecriture->setComptecredit($remboursement->getFinancement()->getCompte());
                    $ecriture->setLibellecomptecredit($remboursement->getFinancement()->getLibellecompte());


            }else{
                    $montant = $solde->montantbanque($entityManager, $remboursement->getBanque()->getCompte());

                    $remboursement->setType('Banque');

                    $debit->setType('Banque');
                    $debit->setCompte($remboursement->getBanque()->getCompte());

                    $ecriture->setType('Banque');
                    $ecriture->setComptedebit($remboursement->getBanque()->getCompte());
                    $ecriture->setLibellecomptedebit($remboursement->getBanque()->getNom());
                    $ecriture->setComptecredit($remboursement->getFinancement()->getCompte());
                    $ecriture->setLibellecomptecredit($remboursement->getFinancement()->getLibellecompte());

                }

            $somme = 0;
            if($remboursement->getFinancement()->getRemboursements() != null){
                foreach ($remboursement->getFinancement()->getRemboursements() as $rembours) {
                    $somme = $somme + $rembours->getMontant();
                }
            }

            if ($remboursement->getMontant() <= $montant && $remboursement->getMontant() <= ($remboursement->getFinancement()->getMontant() - $somme)) {
                $debit->setMontant($remboursement->getMontant());
                $ecriture->setDebit($debit);
                $ecriture->setLibelle($remboursement->getLibele());
                $ecriture->setSolde(-$remboursement->getMontant());
                $ecriture->setMontant($remboursement->getMontant());

                $entityManager->persist($remboursement);
                $entityManager->persist($debit);
                $entityManager->persist($ecriture);
                $entityManager->flush();

                $entityManager->persist($remboursement);
                $entityManager->flush();

                return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('notice', 'Montant non disponible ou supérieur au montant restant');
            }

        }

        return $this->render('remboursement/financement_espece.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/financementPret", name :"remboursement_banque", methods : ["GET","POST"]) ]
    public function financementpret(Request $request, Solde $solde): Response
    {
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementbancaireType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->entityManager;

            $remboursement->setCompte($remboursement->getFinancement()->getCompte());

            $debit = new Debit();
            $debitinteret = new Debit();
            $ecriture = new Ecriture();
            $ecritureinteret = new Ecriture();

            $montant = $solde->montantbanque($entityManager, $remboursement->getFinancement()->getBanque()->getCompte());
//            $totalinteret =  $remboursement->getMontant() * $remboursement->getFinancement()->getTaux() / 100;

            $somme = 0;
            if($remboursement->getFinancement()->getRemboursements() != null){
                foreach ($remboursement->getFinancement()->getRemboursements() as $rembours) {
                    $somme = $somme + $rembours->getMontant();
                    }
            }

            if ($remboursement->getMontant()<= $montant && $remboursement->getMontant() <= ($remboursement->getFinancement()->getMontant() - $somme)) {
//            if (($remboursement->getMontant() + $totalinteret) <= $montant && $remboursement->getMontant() <= ($remboursement->getFinancement()->getMontant() - $somme)) {

            $interet = ($remboursement->getFinancement()->getMontant() * $remboursement->getFinancement()->getTaux()/100) / $remboursement->getFinancement()->getDuree(); 


            $remboursement->setType('Banque');
            $remboursement->setBanque($remboursement->getFinancement()->getBanque());

            $debit->setType('Banque');
            $debit->setCompte($remboursement->getFinancement()->getBanque()->getCompte());
            $debit->setMontant($remboursement->getMontant());
            $debit->setRemboursement($remboursement);

//
            $debitinteret->setType('Banque');
            $debitinteret->setCompte($remboursement->getFinancement()->getBanque()->getCompte());
            $debitinteret->setMontant($interet);
            $debitinteret->setRemboursement($remboursement);



            $ecriture->setType('Banque');
            $ecriture->setComptedebit($remboursement->getFinancement()->getBanque()->getCompte());
            $ecriture->setLibellecomptedebit($remboursement->getFinancement()->getBanque()->getNom());
            $ecriture->setComptecredit($remboursement->getFinancement()->getCompte());
            $ecriture->setLibellecomptecredit($remboursement->getFinancement()->getLibellecompte());
            $ecriture->setDebit($debit);
            $ecriture->setLibelle($remboursement->getLibele());
            $ecriture->setSolde(-$remboursement->getMontant());
            $ecriture->setMontant($remboursement->getMontant());

            $ecritureinteret->setType('Banque');
            $ecritureinteret->setComptedebit($remboursement->getFinancement()->getBanque()->getCompte());
            $ecritureinteret->setLibellecomptedebit($remboursement->getFinancement()->getBanque()->getNom());
            $ecritureinteret->setComptecredit('671200');   
            $ecritureinteret->setLibellecomptecredit("interet sur emprunt au pres des etablissements de crédit");
            $ecritureinteret->setDebit($debitinteret);
            $ecritureinteret->setLibelle('interet '. $remboursement->getLibele());
            $ecritureinteret->setSolde(-$interet);
            $ecritureinteret->setMontant($interet);

            $entityManager->persist($remboursement);
            $entityManager->persist($debit);
            $entityManager->persist($ecriture);
            $entityManager->persist($debitinteret);
            $entityManager->persist($ecritureinteret);
            $entityManager->flush();

            $somme = 0;
            
            foreach ($remboursement->getFinancement()->getRemboursements() as $rembours) {
                $somme = $somme + $rembours->getMontant();
            }
            
            if($remboursement->getFinancement()->getMontant() == $somme){
                $remboursement->getFinancement()->setRembourser(true);
                $entityManager->persist($remboursement->getFinancement());
                $entityManager->flush();
            }

            

            return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
        }else{
            $this->addFlash('notice', 'Montant non disponible ou supérieur au montant restant');
        }
        }

        return $this->render('remboursement/financement_bancaire.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/avoir/{avoir}", name :"remboursement_avoir", methods : ["GET","POST"]) ]
    public function avoir(Avoir $avoir, AvoirResteRepository $avoirResteRepository,Request $request, Solde $solde): Response
    {
        $remboursement = new Remboursement();
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->remove('montant');
        $form->remove('libele');
        $form->remove('financement');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->entityManager;
            $remboursement->setCompte($avoir->getClient()->getCompte());
            $remboursement->setMontant($avoir->getMontant());
            $remboursement->setLibele('Remboursement avoir');

            $debit = new Debit();
            $ecriture = new Ecriture();
            $debit->setRemboursement($remboursement);
            $montant = 0;

            if($remboursement->getType() == 'Espece') {
                $montant = $solde->montantcaisse($entityManager, 571);

                $remboursement->setType('Espece');

                $debit->setType('Espece');
                $debit->setCompte('571');

                $ecriture->setType('Espece');
                $ecriture->setComptedebit('571');
                $ecriture->setLibellecomptedebit('Caisse');
                $ecriture->setComptecredit($avoir->getClient()->getCompte());
                $ecriture->setLibellecomptecredit("Compte Client");



            }else{
                $montant = $solde->montantcaisse($entityManager, $remboursement->getBanque()->getCompte());

                $remboursement->setType('Banque');

                $debit->setType('Banque');
                $debit->setCompte($remboursement->getBanque()->getCompte());

                $ecriture->setType('Banque');
                $ecriture->setLibellecomptedebit($remboursement->getBanque()->getNom());
                $ecriture->setComptecredit($avoir->getClient()->getCompte());
                $ecriture->setLibellecomptecredit("Compte Client");

            }



            if ($remboursement->getMontant() <= $montant) {
                $avoir->setRebourser(true);
                $remboursement->setAvoir($avoir);
                $debit->setMontant($remboursement->getMontant());
                $ecriture->setDebit($debit);
                $ecriture->setLibelle($remboursement->getLibele());
                $ecriture->setSolde(-$remboursement->getMontant());
                $ecriture->setMontant($remboursement->getMontant());

                $entityManager->persist($avoir);
                $entityManager->persist($remboursement);
                $entityManager->persist($debit);
                $entityManager->persist($ecriture);
                $entityManager->flush();

                $entityManager->persist($remboursement);
                $entityManager->flush();
                return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
            }else{
                $this->addFlash('notice', 'Montant non disponible');
            }
        }

        return $this->render('remboursement/avoir.html.twig', [
            'avoir' => $avoir,
            'details' => $avoirResteRepository->findBy(['avoir' => $avoir]),
            'remboursement' => $remboursement,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}", name :"remboursement_show", methods : ["GET"]) ]
    public function show(Remboursement $remboursement): Response
    {
        return $this->render('remboursement/show.html.twig', [
            'remboursement' => $remboursement,
        ]);
    }

    #[Route("/{id}/edit", name :"remboursement_edit", methods : ["GET","POST"]) ]
    public function edit(Request $request, Remboursement $remboursement): Response
    {
        $form = $this->createForm(RemboursementType::class, $remboursement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('remboursement/edit.html.twig', [
            'remboursement' => $remboursement,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}", name :"remboursement_delete", methods : ["POST"]) ]
    public function delete(Request $request, Remboursement $remboursement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$remboursement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->entityManager;
            $entityManager->remove($remboursement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('remboursement_index', [], Response::HTTP_SEE_OTHER);
    }
}
