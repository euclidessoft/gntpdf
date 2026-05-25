<?php

namespace App\Controller;

use App\Entity\Accompte;
use App\Form\AccompteForm;
use App\Repository\AccompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/{_locale}/accompte')]
final class AccompteController extends AbstractController
{
    public function __construct(private Security $security)
    {
    }

    #[Route(name: 'app_accompte_index', methods: ['GET'])]
    public function index(AccompteRepository $accompteRepository): Response
    {
        if ($this->security->isGranted('ROLE_RH')) {
       
        $response = $this->render('accompte/index.html.twig', [
            'accomptes' => $accompteRepository->findBy(['paye' => false], ['id' => 'DESC']),
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
    
    #[Route('/paye', name: 'app_accompte_paye', methods: ['GET'])]
    public function paye(AccompteRepository $accompteRepository): Response
    {
        if ($this->security->isGranted('ROLE_RH')) {
       
        $response = $this->render('accompte/paye.html.twig', [
            'accomptes' => $accompteRepository->findBy(['paye' => true], ['id' => 'DESC']),
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

    #[Route('/new', name: 'app_accompte_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->security->isGranted('ROLE_RH')) {
        $accompte = new Accompte();
        $form = $this->createForm(AccompteForm::class, $accompte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accomp= $entityManager->getRepository(Accompte::class)->findOneBy(['employe' => $accompte->getEmploye()->getId(), 'paye' => false], ['id' =>'DESC']);
            if(empty($accomp)){
                if($accompte->getMontant() <= $accompte->getEmploye()->getPoste()->getSalaire()){
                    $entityManager->persist($accompte);
                    $entityManager->flush();

                    $this->addFlash('notice', 'enrgistre avec succes');
                    $response = $this->redirectToRoute('app_accompte_index', [], Response::HTTP_SEE_OTHER);
                        $response->setSharedMaxAge(0);
                        $response->headers->addCacheControlDirective('no-cache', true);
                        $response->headers->addCacheControlDirective('no-store', true);
                        $response->headers->addCacheControlDirective('must-revalidate', true);
                        $response->setCache([
                            'max_age' => 0,
                            'private' => true,
                        ]);
                        return $response;
                }else{
                        $this->addFlash('notice', 'l\'accompte ne peut etre suprerieur au salaire de base');
                    }
            }else{
            $this->addFlash('notice', 'l\'employé a déjà un accompte non remboursé');
             }
        }

       
        $response = $this->render('accompte/new.html.twig', [
            'accompte' => $accompte,
            'form' => $form,
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

    #[Route('/{id}', name: 'app_accompte_show', methods: ['GET'])]
    public function show(Accompte $accompte): Response
    {
        if ($this->security->isGranted('ROLE_RH')) {
       
        $response = $this->render('accompte/show.html.twig', [
            'accompte' => $accompte,
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

    #[Route('/{id}/edit', name: 'app_accompte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Accompte $accompte, EntityManagerInterface $entityManager): Response
    {
        if ($this->security->isGranted('ROLE_RH')) {// && !$accompte->getVerser()
           

                $form = $this->createForm(AccompteForm::class, $accompte);
                $form->remove('employe');
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid() && !$accompte->getVerser()) {
                    if (!$accompte->getVerser()) {
                        if($accompte->getMontant() <= $accompte->getEmploye()->getPoste()->getSalaire()){
                        $entityManager->flush();

                       
                        $response = $this->redirectToRoute('app_accompte_index', [], Response::HTTP_SEE_OTHER);
                        $response->setSharedMaxAge(0);
                        $response->headers->addCacheControlDirective('no-cache', true);
                        $response->headers->addCacheControlDirective('no-store', true);
                        $response->headers->addCacheControlDirective('must-revalidate', true);
                        $response->setCache([
                            'max_age' => 0,
                            'private' => true,
                        ]);
                        return $response;
                    }else{
                        $this->addFlash('notice', 'l\'accompte ne peut etre suprerieur au salaire de base');
                    }
                    }else  $this->addFlash('notice', 'Accompte deja verse');
                }
            

       
            $response = $this->render('accompte/edit.html.twig', [
                'accompte' => $accompte,
                'form' => $form,
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

    #[Route('/{id}', name: 'app_accompte_delete', methods: ['POST'])]
    public function delete(Request $request, Accompte $accompte, EntityManagerInterface $entityManager): Response
    {
        if ($this->security->isGranted('ROLE_RH')) {
        if ($this->isCsrfTokenValid('delete'.$accompte->getId(), $request->getPayload()->getString('_token')) && !$accompte->getVerser()) {
            $entityManager->remove($accompte);
            $entityManager->flush();
       
        }else $this->addFlash('notice', 'Accompte deja verse');

        
        $response = $this->redirectToRoute('app_accompte_index', [], Response::HTTP_SEE_OTHER);
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
