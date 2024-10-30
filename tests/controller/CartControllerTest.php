<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\DataFixtures\SweatshirtFixtures;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class CartControllerTest extends WebTestCase
{
    private $client;
    private $sweatshirtIds;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        // Purger la base de données pour assurer un état propre avant chaque test
        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $purger = new ORMPurger($entityManager);
        $purger->purge();

        // Charger les fixtures de Sweatshirt
        $sweatshirtFixtures = new SweatshirtFixtures();
        $sweatshirtFixtures->load($entityManager);

        // Récupérer les IDs dynamiquement
        $this->sweatshirtIds = $sweatshirtFixtures->getSweatshirtIds(); 
    }

    public function testAddItemToCart(): void
    {
        $this->addItemToCart($this->sweatshirtIds[0], 'S');

        // Vérifier que la réponse redirige vers la page d'index du panier
        $this->assertResponseRedirects('/cart/', 302);

        // Suivre la redirection vers la page du panier
        $this->client->followRedirect();

        // Vérifier que le sweatshirt est maintenant dans le panier
        $this->assertSelectorTextContains('.cart-item', 'SweatShirt 1');
        $this->assertSelectorTextContains('.cart-item', 'Taille : S'); 
    }

    public function testCheckoutSessionCreationWithItems(): void
    {
        // Ajouter un article au panier
        $this->addItemToCart($this->sweatshirtIds[0], 'S');

        // Simuler la création de la session de paiement
        $this->client->request('POST', '/create-checkout-session');

        // Vérifier que la réponse est un JSON valide avec un ID de session
        $response = $this->client->getResponse();
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $data); // Vérifier que l'ID de session est présent

    }

    public function testSuccessfulPaymentRedirect(): void
    {
        // Ajouter un article au panier
        $this->addItemToCart($this->sweatshirtIds[0], 'S');

        // Créer la session de paiement
        $this->client->request('POST', '/create-checkout-session');
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $sessionId = $data['id'];


        // Accéder à la route de succès
        $this->client->request('GET', '/success');

        // Vérifier que la page de succès est affichée
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Paiement réussi');
    }

    private function addItemToCart(string $sweatshirtId, string $size): void
    {
        $this->client->request('POST', '/cart/add/' . $sweatshirtId . '/' . $size);
    }
}
