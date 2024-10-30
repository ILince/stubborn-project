<?php

namespace App\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\DataFixtures\UserFixtures;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager = null; // Instance de l'Entity Manager
    private ?UserRepository $userRepository = null; // Instance du UserRepository

    protected function setUp(): void
    {
        // Démarrer le noyau Symfony
        self::bootKernel();

        // Récupérer l'Entity Manager et le UserRepository depuis le conteneur
        $this->entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->userRepository = self::getContainer()->get(UserRepository::class);

        // Charger les fixtures d'utilisateur
        $passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);
        $userFixtures = new UserFixtures($passwordHasher);
        $userFixtures->load($this->entityManager);
    }

    public function testCount()
    {
        // Obtenir le nombre d'utilisateurs dans le repository
        $usersCount = $this->userRepository->count([]);

        // Vérifier qu'il y a 10 utilisateurs
        $this->assertEquals(10, $usersCount);
    }

    protected function tearDown(): void
    {
        // Nettoyer l'EntityManager et autres ressources
        parent::tearDown();

        if ($this->entityManager) {
            $this->entityManager->close(); // Fermer l'EntityManager
            $this->entityManager = null; // Éviter les fuites de mémoire
        }
        
        $this->userRepository = null; // Libérer la référence
    }
}
