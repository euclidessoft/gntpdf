<?php

namespace App\Controller;

use App\Entity\HeureSuplementaire;
use App\Form\HeureSuplementaireType;
use App\Repository\HeureSuplementaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route("/{_locale}/HeureSuplementaire") ]
class HeureSuplementaireController extends AbstractController
{
    public function __construct(private Security $security, private EntityManagerInterface $entityManager)
    {
    }

    #[Route("/", name :"heure_suplementaire_index", methods : ["GET"]) ]
    public function index(HeureSuplementaireRepository $heureSuplementaireRepository): Response
    {
        if ($this->security->isGranted('ROLE_RH')) {
           
            $response = $this->render('heure_suplementaire/admin/index.html.twig', [
                'heure_suplementaires' => $heureSuplementaireRepository->findBy(['paye' => false]),
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        } else {
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

    #[Route("/paye", name :"heure_suplementaire_paye", methods : ["GET"]) ]
    public function paye(HeureSuplementaireRepository $heureSuplementaireRepository): Response
    {
        if ($this->security->isGranted('ROLE_RH')) {
           
            $response = $this->render('heure_suplementaire/admin/paye.html.twig', [
                'heure_suplementaires' => $heureSuplementaireRepository->findBy(['paye' => true]),
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        } else {
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

    #[Route("/Suivi", name :"heure_suivi", methods : ["GET"]) ]
    public function suivi(Security $security): Response
    {
        if ($this->security->isGranted('ROLE_EMPLOYER')) {
            $entityManager = $this->entityManager;
            $employe = $security->getUser();
            $heures = $entityManager->getRepository(HeureSuplementaire::class)->findBy(['employe' => $employe]);
           
            $response = $this->render('heure_suplementaire/index.html.twig', [
                'heures' => $heures,
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        } else {
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

    #[Route("/new", name :"heure_suplementaire_new", methods : ["GET","POST"]) ]
    public function new(Request $request): Response
    {
        if ($this->security->isGranted('ROLE_RH')) {
            $heureSuplementaire = new HeureSuplementaire();
            $form = $this->createForm(HeureSuplementaireType::class, $heureSuplementaire);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->entityManager;
                $heureSuplementaire->setCreatedAt(new \DateTime());
                if($heureSuplementaire->getEmploye()->getPoste()->getHeureSup() !== null) {
                 $heureSuplementaire->setTauxHoraire($heureSuplementaire->getEmploye()->getPoste()->getHeureSup());
                 }else{
                     $heureSuplementaire->setTauxHoraire($heureSuplementaire->getEmploye()->getPoste()->getSalaire()/173.33);
                 } 
                $entityManager->persist($heureSuplementaire);
                $entityManager->flush();

                $response = $this->redirectToRoute('heure_suplementaire_index', [], Response::HTTP_SEE_OTHER);
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

           
            $response = $this->render('heure_suplementaire/admin/new.html.twig', [
                'heure_suplementaire' => $heureSuplementaire,
                'form' => $form->createView(),
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        } else {
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

    #[Route("/{id}", name :"heure_suplementaire_show", methods : ["GET"]) ]
    public function show(HeureSuplementaire $heureSuplementaire): Response
    {
        if ($this->security->isGranted('ROLE_RH')) {
           
            $response = $this->render('heure_suplementaire/admin/show.html.twig', [
                'heure_suplementaire' => $heureSuplementaire,
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        } else {
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

    #[Route("/{id}/edit", name :"heure_suplementaire_edit", methods : ["GET","POST"]) ]
    public function edit(Request $request, HeureSuplementaire $heureSuplementaire): Response
    {
        if ($this->security->isGranted('ROLE_RH') && !$heureSuplementaire->isPaye()) {
            $form = $this->createForm(HeureSuplementaireType::class, $heureSuplementaire);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

               
                $response = $this->redirectToRoute('heure_suplementaire_index', [], Response::HTTP_SEE_OTHER);
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

            $response = $this->render('heure_suplementaire/admin/edit.html.twig', [
                'heure_suplementaire' => $heureSuplementaire,
                'form' => $form->createView(),
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        } else {
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

    #[Route("/{id}", name :"heure_suplementaire_delete", methods : ["POST"]) ]
    public function delete(Request $request, HeureSuplementaire $heureSuplementaire): Response
    {
        if ($this->security->isGranted('ROLE_RH')) {
            if ($this->isCsrfTokenValid('delete' . $heureSuplementaire->getId(), $request->request->get('_token')) && !$heureSuplementaire->isPaye()) {
                $entityManager = $this->entityManager;
                $entityManager->remove($heureSuplementaire);
                $entityManager->flush();
            }

            
            $response = $this->redirectToRoute('heure_suplementaire_index', [], Response::HTTP_SEE_OTHER);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        } else {
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


}
