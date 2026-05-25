<?php

namespace App\Controller;

use App\Entity\Subvention;
use App\Form\SubventionForm;
use App\Repository\SubventionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/subvention')]
final class SubventionController extends AbstractController
{
    #[Route(name: 'app_subvention_index', methods: ['GET'])]
    public function index(SubventionRepository $subventionRepository): Response
    {
        return $this->render('subvention/index.html.twig', [
            'subventions' => $subventionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_subvention_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subvention = new Subvention();
        $form = $this->createForm(SubventionForm::class, $subvention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subvention);
            $entityManager->flush();

            return $this->redirectToRoute('app_subvention_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subvention/new.html.twig', [
            'subvention' => $subvention,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subvention_show', methods: ['GET'])]
    public function show(Subvention $subvention): Response
    {
        return $this->render('subvention/show.html.twig', [
            'subvention' => $subvention,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_subvention_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subvention $subvention, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubventionForm::class, $subvention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_subvention_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subvention/edit.html.twig', [
            'subvention' => $subvention,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subvention_delete', methods: ['POST'])]
    public function delete(Request $request, Subvention $subvention, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subvention->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($subvention);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_subvention_index', [], Response::HTTP_SEE_OTHER);
    }
}
