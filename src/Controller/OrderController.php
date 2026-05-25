<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/{_locale}/order", name :"order_") ]
class OrderController extends AbstractController
{
       public function __construct(private Security $security, private EntityManagerInterface $entityManager)
    {
    }
    #[Route("/", name :"index") ]
    public function index(): Response
    {
        $panier = 1;
        return $this->render('commande/allcommande.html.twig', [
            'panier' => $panier,
        ]);
    }
    
}
