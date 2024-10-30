<?php

// src/DataFixtures/SweatshirtFixtures.php

namespace App\DataFixtures;

use App\Entity\SweatShirt;
use App\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SweatshirtFixtures extends Fixture
{
    // Tableau pour stocker les IDs des sweatshirts créés
    private $sweatshirtIds = []; 

    // La méthode load est appelée pour charger les données dans la base de données
    public function load(ObjectManager $manager): void
    {
        // Création de 5 sweatshirts avec des informations de base
        for ($i = 1; $i <= 5; $i++) {
            $sweatShirt = new SweatShirt();
            $sweatShirt->setName("SweatShirt $i")
                        ->setPrice(29.99 + $i) 
                        ->setThumbnail("thumbnail_$i.jpg"); 

            // Création des tailles de stock pour chaque sweatshirt
            foreach (['S', 'M', 'L'] as $size) {
                $stock = new Stock();
                $stock->setSize($size) 
                      ->setQuantity(rand(1, 100)) 
                      ->setSweatshirt($sweatShirt); 
                $manager->persist($stock); 
                $sweatShirt->addStock($stock);
            }

            $manager->persist($sweatShirt); // Persister le sweatshirt
        }

        $manager->flush(); // Enregistrer toutes les entités persistées dans la base de données

        // Récupération des IDs des sweatshirts créés pour d'autres utilisations
        foreach ($manager->getRepository(SweatShirt::class)->findAll() as $sweatshirt) {
            $this->sweatshirtIds[] = $sweatshirt->getId(); // Stocker l'ID dans le tableau
        }
    }

    // Méthode pour récupérer les IDs des sweatshirts créés
    public function getSweatshirtIds(): array
    {
        return $this->sweatshirtIds; // Retourner le tableau des IDs
    }
}
