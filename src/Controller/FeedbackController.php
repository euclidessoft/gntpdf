<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Form\FeedbackType;
use App\Repository\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\SecurityBundle\Security;

#[Route("/{_locale}/Feedback") ]
class FeedbackController extends AbstractController
{ 
      public function __construct(private Security $security, private EntityManagerInterface $entityManager)
    {
    }

    #[Route("/", name :"feedback_index", methods : ["GET"]) ]
    public function index(FeedbackRepository $feedbackRepository): Response
    {
        if ($this->security->isGranted('ROLE_EMPLOYER')) {
            return $this->render('feedback/admin/index.html.twig', [
                'feedback' => $feedbackRepository->findAll(),
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

    #[Route("/new", name :"feedback_new", methods : ["GET","POST"]) ]
    public function new(Request $request, Security $security): Response
    {
        if ($this->security->isGranted('ROLE_EMPLOYER')) {
            $feedback = new Feedback();
            $form = $this->createForm(FeedbackType::class, $feedback);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
//                $entityManager = $this->getDoctrine()->getManager();
                $employe = $security->getUser();
                $feedback->setCreatedAt(new \DateTime());
                $feedback->setEmploye($employe);

                $this->entityManager->persist($feedback);
                $this->entityManager->flush();

                return $this->redirectToRoute('feedback_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('feedback/admin/new.html.twig', [
                'feedback' => $feedback,
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


    #[Route("/Suivi", name :"feedback_suivi", methods : ["GET"]) ]
    public function suivi(Security $security): Response
    {
        if ($this->security->isGranted('ROLE_EMPLOYER')) {
            $employe = $security->getUser();
            $feedback = $this->entityManager->getRepository(Feedback::class)->findBy(['employe' => $employe]);

            return $this->render("feedback/index.html.twig", [
                'feedback' => $feedback,
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

    #[Route("/Suivi/{id}", name :"feedback_suivi_show", methods : ["GET"]) ]
    public function suiviShow(Feedback $feedback): Response
    {
        if ($this->security->isGranted('ROLE_EMPLOYER')) {
            return $this->render("feedback/show.html.twig", [
                'feedback' => $feedback,
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


    #[Route("/{id}", name :"feedback_show", methods : ["GET"]) ]
    public function show(Feedback $feedback): Response
    {
        if ($this->security->isGranted('ROLE_EMPLOYER')) {
            return $this->render('feedback/admin/show.html.twig', [
                'feedback' => $feedback,
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

    #[Route("/{id}/edit", name :"feedback_edit", methods : ["GET","POST"]) ]
    public function edit(Request $request, Feedback $feedback): Response
    {
        if ($this->security->isGranted('ROLE_EMPLOYER')) {
            $form = $this->createForm(FeedbackType::class, $feedback);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                return $this->redirectToRoute('feedback_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('feedback/admin/edit.html.twig', [
                'feedback' => $feedback,
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

    #[Route("/{id}", name :"feedback_delete", methods : ["POST"]) ]
    public function delete(Request $request, Feedback $feedback): Response
    {
        if ($this->security->isGranted('ROLE_EMPLOYER')) {
            if ($this->isCsrfTokenValid('delete' . $feedback->getId(), $request->request->get('_token'))) {
//                $entityManager = $this->getDoctrine()->getManager();
                $this->entityManager->remove($feedback);
                $this->entityManager->flush();
            }

            return $this->redirectToRoute('feedback_index', [], Response::HTTP_SEE_OTHER);
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
