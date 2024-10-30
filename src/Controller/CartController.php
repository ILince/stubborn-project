<?php

namespace App\Controller;

use App\Entity\SweatShirt;
use App\Repository\SweatshirtRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

// Contrôleur pour la gestion du panier
#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{
    // Affiche le contenu du panier
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, SweatshirtRepository $sweatshirtRepository): Response
    {
        $panier = $session->get('panier', []);
        $data = [];
        $total = 0;

        // Parcourt les articles du panier pour récupérer les informations nécessaires
        foreach ($panier as $key => $quantity) {
            $parts = explode('-', $key);
            if (count($parts) === 2) {
                [$id, $size] = $parts;
                $sweatShirt = $sweatshirtRepository->find($id);
                if ($sweatShirt) {
                    $data[] = [
                        'sweatShirt' => $sweatShirt,
                        'quantite' => $quantity,
                        'size' => $size,
                    ];
                    $total += $sweatShirt->getPrice() * $quantity;
                }
            }
        }

        return $this->render('cart/cart.html.twig', [
            'data' => $data,
            'total' => $total,
        ]);
    }

    // Ajoute un article au panier
    #[Route('/add/{id}/{size}', name: 'add')]
    public function add(SweatShirt $sweatShirt, string $size, SessionInterface $session): Response
    {
        $id = $sweatShirt->getId();
        $panier = $session->get('panier', []);
        $key = $id . '-' . $size;

        // Ajoute ou augmente la quantité de l'article dans le panier
        if (empty($panier[$key])) {
            $panier[$key] = 1;
        } else {
            $panier[$key]++;
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute('app_cart_index');
    }

    // Supprime un article du panier
    #[Route('/remove/{id}/{size}', name: 'remove')]
    public function remove(string $id, string $size, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        $key = $id . '-' . $size;

        // Vérifie et supprime l'article du panier si présent
        if (isset($panier[$key])) {
            unset($panier[$key]);
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute('app_cart_index');
    }
}
