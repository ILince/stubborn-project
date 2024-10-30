<?php

namespace App\Controller;

use App\Entity\SweatShirt;
use App\Repository\SweatshirtRepository;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripePaymentController extends AbstractController
{
    private $sweatshirtRepository;

    // Injection du repository SweatShirt dans le constructeur
    public function __construct(SweatshirtRepository $sweatshirtRepository)
    {
        $this->sweatshirtRepository = $sweatshirtRepository;
    }

    // Route pour afficher la page de checkout
    #[Route('/checkout', name: 'checkout')]
    public function checkoutPage(SessionInterface $session)
    {
        // Récupération des articles du panier depuis la session
        $cart = $session->get('panier', []);

        // Utilisation de la méthode getUserCartItems pour récupérer les articles
        $data = $this->getUserCartItems($cart);

        return $this->render('stripe_payment/checkout.html.twig', [
            'stripe_public_key' => $this->getParameter('stripe_public_key'),
            'data' => $data,
        ]);
    }

    // Méthode pour récupérer les articles du panier de l'utilisateur
    private function getUserCartItems(array $cart)
    {
        $items = [];

        foreach ($cart as $key => $quantity) {
            // Diviser l'ID et la taille comme dans le contrôleur de panier
            $parts = explode('-', $key);
            if (count($parts) === 2) {
                [$id, $size] = $parts;

                // Récupérer l'article SweatShirt depuis le repository
                $sweatShirt = $this->sweatshirtRepository->find($id);

                if ($sweatShirt) {
                    $items[] = [
                        'sweatShirt' => $sweatShirt,
                        'quantity' => $quantity,
                        'size' => $size
                    ];
                }
            }
        }

        return $items;
    }

    // Route pour créer la session de paiement Stripe
    #[Route('/create-checkout-session', name: 'create_checkout_session', methods: ['POST'])]
    public function createCheckoutSession(Request $request, SessionInterface $session): JsonResponse
    {
        Stripe::setApiKey($this->getParameter('stripe_secret_key'));

        // Récupération des articles du panier pour la session Stripe
        $cart = $session->get('panier', []);
        $items = $this->getUserCartItems($cart);

        $line_items = [];
        foreach ($items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['sweatShirt']->getName(), // Nom de l'article
                    ],
                    'unit_amount' => $item['sweatShirt']->getPrice() * 100, // Prix en centimes
                ],
                'quantity' => $item['quantity'],
            ];
        }

        // Création de la session de paiement Stripe
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new JsonResponse(['id' => $session->id]);
    }

    // Route pour afficher la page de succès du paiement
    #[Route('/success', name: 'success')]
    public function success()
    {
        return $this->render('stripe_payment/success.html.twig');
    }

    // Route pour afficher la page d'annulation du paiement
    #[Route('/cancel', name: 'cancel')]
    public function cancel()
    {
        return $this->render('stripe_payment/cancel.html.twig');
    }
}
