<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SweatshirtRepository;

class HomeController extends AbstractController
{
    // Route pour la page d'accueil
    #[Route('/', name: 'app_home')]
    public function index(SweatshirtRepository $sweatshirtRepository): Response
    {
        // IDs des sweatshirts à afficher
        $sweatshirtIds = [1, 4, 9];

        // Récupération des sweatshirts en fonction des IDs
        $sweatshirts = $sweatshirtRepository->findBy(['id' => $sweatshirtIds]);

        return $this->render('home/home.html.twig', [
            'sweatshirts' => $sweatshirts,
        ]);
    }
}
