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
        // Liste des noms de sweatshirts à afficher
        $sweatshirtNames = ['Blackbelt', 'Street', 'BornInUsa'];

        // Récupérer les sweatshirts en fonction des noms
        $sweatshirts = $sweatshirtRepository->findBy(['name' => $sweatshirtNames]);

        return $this->render('home/home.html.twig', [
            'sweatshirts' => $sweatshirts,
        ]);
    }
}
