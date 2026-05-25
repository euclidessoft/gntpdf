<?php

namespace App\Controller;

use App\Entity\PrimePerformance;
use App\Form\PrimePerformanceType;
use App\Repository\PrimePerformanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route("/{_locale}/prime_performance") ]
class PrimePerformanceController extends AbstractController
{
    public function __construct(private Security $security, private EntityManagerInterface $entityManager)
    {
    }

#[Route("/", name :"prime_performance_index", methods : ["GET"]) ]
    public function index(PrimePerformanceRepository $primePerformanceRepository): Response
    {
         if ($this->security->isGranted('ROLE_RH')) {
       
        $response = $this->render('prime_performance/index.html.twig', [
            'prime_performances' => $primePerformanceRepository->findBy(['paye' => false]),
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

    #[Route("/paye", name :"prime_performance_paye", methods : ["GET"]) ]
    public function paye(PrimePerformanceRepository $primePerformanceRepository): Response
    {
         if ($this->security->isGranted('ROLE_RH')) {
        
        $response = $this->render('prime_performance/paye.html.twig', [
            'prime_performances' => $primePerformanceRepository->findBy(['paye' => true]),
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

    #[Route("/new", name :"prime_performance_new", methods : ["GET","POST"]) ]
    public function new(Request $request): Response
    {
         if ($this->security->isGranted('ROLE_RH')) {
        $primePerformance = new PrimePerformance();
        $form = $this->createForm(PrimePerformanceType::class, $primePerformance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->entityManager;
            $entityManager->persist($primePerformance);
            $entityManager->flush();

           
            $response = $this->redirectToRoute('prime_performance_index', [], Response::HTTP_SEE_OTHER);
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

       
        $response = $this->render('prime_performance/new.html.twig', [
            'prime_performance' => $primePerformance,
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

    #[Route("/{id}", name :"prime_performance_show", methods : ["GET"]) ]
    public function show(PrimePerformance $primePerformance): Response
    {
         if ($this->security->isGranted('ROLE_RH')) {
       
        $response = $this->render('prime_performance/show.html.twig', [
            'prime_performance' => $primePerformance,
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

    #[Route("/{id}/edit", name :"prime_performance_edit", methods : ["GET","POST"]) ]
    public function edit(Request $request, PrimePerformance $primePerformance): Response
    {
         if ($this->security->isGranted('ROLE_RH') && !$primePerformance->isPaye()) {
        $form = $this->createForm(PrimePerformanceType::class, $primePerformance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

           
            $response = $this->redirectToRoute('prime_performance_index', [], Response::HTTP_SEE_OTHER);
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

       
        $response = $this->render('prime_performance/edit.html.twig', [
            'prime_performance' => $primePerformance,
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

    #[Route("/{id}", name :"prime_performance_delete", methods : ["POST"]) ]
    public function delete(Request $request, PrimePerformance $primePerformance): Response
    {
         if ($this->security->isGranted('ROLE_RH')) {
        if ($this->isCsrfTokenValid('delete'.$primePerformance->getId(), $request->request->get('_token')) && !$primePerformance->isPaye()) {
            $entityManager = $this->entityManager;
            $entityManager->remove($primePerformance);
            $entityManager->flush();
        }

      
        $response = $this->redirectToRoute('prime_performance_index', [], Response::HTTP_SEE_OTHER);
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
