<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    // Interface pour le hachage de mot de passe
    private UserPasswordHasherInterface $passwordHasher;

    // Injection du hachageur de mot de passe dans le constructeur
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    // Méthode pour charger les données dans la base de données
    public function load(ObjectManager $manager)
    {
        // Création de 10 utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $username = "user_$i";
    
            // Vérification si le nom d'utilisateur existe déjà
            while ($manager->getRepository(User::class)->findOneBy(['username' => $username])) {
                $i++; // Incrémenter pour générer un nouveau nom d'utilisateur
                $username = "user_$i"; 
            }
    
            // Création d'une nouvelle entité User
            $user = new User();
            $user->setEmail("user$i@domain.fr"); 
            $user->setUsername($username); 
    
            // Hacher le mot de passe avant de le définir
            $hashedPassword = $this->passwordHasher->hashPassword($user, "0000"); 
            $user->setPassword($hashedPassword); 
            $user->setDeliveryAddress("Address for user $i"); 
    
            $manager->persist($user); // Persister l'utilisateur
        }
    
        $manager->flush(); // Enregistrer toutes les entités persistées dans la base de données
    }
}
