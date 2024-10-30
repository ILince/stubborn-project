<?php

namespace App\Controller;

use App\Entity\SweatShirt;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProductIdController extends AbstractController
{
    // Route pour afficher un produit spécifique selon son ID
    #[Route('/product/{id}', name: 'app_product_id')]
    public function index(int $id, EntityManagerInterface $entityManager): Response
    {
        // Récupération du produit par son ID
        $sweatshirt = $entityManager->getRepository(SweatShirt::class)->find($id);

        // Vérification si le produit existe
        if (!$sweatshirt) {
            throw $this->createNotFoundException('Le produit n\'existe pas.');
        }

        // Affichage de la vue avec les données du produit
        return $this->render('product_id/productid.html.twig', [
            'sweatshirt' => $sweatshirt,
        ]);
    }
}
