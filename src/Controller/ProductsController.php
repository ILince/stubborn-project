<?php

namespace App\Controller;

use App\Repository\SweatshirtRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    // Route pour afficher la liste des produits
    #[Route('/products', name: 'app_products')]
    public function index(Request $request, SweatshirtRepository $sweatshirtRepository): Response
    {
        // Récupérer la fourchette de prix de la requête HTTP
        $priceRange = $request->query->get('price-range');
        
        // Filtrer les produits selon la fourchette de prix si celle-ci est définie
        if ($priceRange) {
            $priceLimits = explode('-', $priceRange);
            $sweatshirts = $sweatshirtRepository->findByPriceRange((int) $priceLimits[0], (int) $priceLimits[1]);
        } else {
            // Récupérer tous les sweatshirts si aucun filtre de prix n'est sélectionné
            $sweatshirts = $sweatshirtRepository->findAll();
        }

        // Afficher la vue avec la liste des produits et la fourchette de prix
        return $this->render('products/products.html.twig', [
            'sweatshirts' => $sweatshirts,
            'priceRange' => $priceRange,
        ]);
    }
}
