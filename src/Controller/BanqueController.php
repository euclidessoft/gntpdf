<?php

namespace App\Controller;

use App\Entity\Banque;
use App\Entity\Compte;
use App\Form\BanqueType;
use App\Repository\BanqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/{_locale}/banque") ]
class BanqueController extends AbstractController
{
    public function __construct(private Security $security, private EntityManagerInterface $entityManager)
    {
    }

    #[Route("/", name :"banque_index", methods : ["GET"]) ]
    public function index(BanqueRepository $banqueRepository): Response
    {
        if ($this->security->isGranted('ROLE_FINANCE')) {
        return $this->render('banque/index.html.twig', [
            'banques' => $banqueRepository->findAll(),
        ]);
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

    #[Route("/new", name :"banque_new", methods : ["GET","POST"]) ]
    public function new(Request $request): Response
    {
        if ($this->security->isGranted('ROLE_FINANCE')) {
        $banque = new Banque();
        $form = $this->createForm(BanqueType::class, $banque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->entityManager;
           
            // $banque->setCompte('52'.$banque->getCompte());
            $compte= new Compte();
            $compte->setNumero($banque->getCompte());
            $compte->setIntitule($banque->getNom());

            $entityManager->persist($compte);
            $entityManager->persist($banque);
            $entityManager->flush();
            return $this->redirectToRoute('banque_index', [], Response::HTTP_SEE_OTHER);
           

            
        }

        return $this->render('banque/new.html.twig', [
            'banque' => $banque,
            'form' => $form->createView(),
        ]);
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

    #[Route("/{id}", name :"banque_show", methods : ["GET"]) ]
    public function show(Banque $banque): Response
    {
       if ($this->security->isGranted('ROLE_FINANCE')) {
        return $this->render('banque/show.html.twig', [
            'banque' => $banque,
        ]);
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

    #[Route("/{id}/edit", name :"banque_edit", methods : ["GET","POST"]) ]
    public function edit(Request $request, Banque $banque): Response
    {
        if ($this->security->isGranted('ROLE_FINANCE')) {
        $form = $this->createForm(BanqueType::class, $banque);
        $form->remove('compte');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('banque_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('banque/edit.html.twig', [
            'banque' => $banque,
            'form' => $form->createView(),
        ]);
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

    #[Route("/{id}", name :"banque_delete", methods : ["POST"]) ]
    public function delete(Request $request, Banque $banque): Response
    {
        if ($this->security->isGranted('ROLE_FINANCE')) {
            try{
                if ($this->isCsrfTokenValid('delete'.$banque->getId(), $request->request->get('_token'))) {
                    $entityManager = $this->entityManager;
                    $entityManager->remove($banque);
                    $entityManager->flush();
                }
            }
            catch (\Exception $exception){
                $this->addFlash('notice', 'Impossible de supprimer pour des raisons de traçabilité');
                return $this->redirectToRoute('banque_index', [], Response::HTTP_SEE_OTHER);

            }

        $response = $this->redirectToRoute('banque_index', [], Response::HTTP_SEE_OTHER);
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
