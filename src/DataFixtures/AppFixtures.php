<?php

namespace App\DataFixtures;

use App\Entity\SweatShirt;
use App\Entity\Stock;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Données des sweatshirts
        $sweatShirtsData = [
            ['name' => 'Blackbelt', 'price' => 29.90, 'thumbnail' => 'Blackbelt.jpeg'],
            ['name' => 'BlueBelt', 'price' => 29.90, 'thumbnail' => 'BlueBelt.jpeg'],
            ['name' => 'Street', 'price' => 34.50, 'thumbnail' => 'Street.jpeg'],
            ['name' => 'Pokeball', 'price' => 45, 'thumbnail' => 'Pokeball.jpeg'],
            ['name' => 'PinkLady', 'price' => 29.90, 'thumbnail' => 'PinkLady.jpeg'],
            ['name' => 'Snow', 'price' => 32, 'thumbnail' => 'Snow.jpeg'],
            ['name' => 'Greyback', 'price' => 28.50, 'thumbnail' => 'Greyback.jpeg'],
            ['name' => 'BlueCloud', 'price' => 45, 'thumbnail' => 'BlueCloud.jpeg'],
            ['name' => 'BornInUsa', 'price' => 59.90, 'thumbnail' => 'BornInUsa.jpeg'],
            ['name' => 'GreenSchool', 'price' => 42.20, 'thumbnail' => 'GreenSchool.jpeg'],
        ];

        $sweatshirts = [];
        foreach ($sweatShirtsData as $data) {
            $sweatshirt = new SweatShirt();
            $sweatshirt->setName($data['name']);
            $sweatshirt->setPrice($data['price']);
            $sweatshirt->setThumbnail($data['thumbnail']);
            $manager->persist($sweatshirt);
            $sweatshirts[] = $sweatshirt;
        }

        // Créer du stock pour chaque sweatshirt
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];
        foreach ($sweatshirts as $sweatshirt) {
            foreach ($sizes as $size) {
                $stock = new Stock();
                $stock->setSweatshirt($sweatshirt);
                $stock->setSize($size);
                $stock->setQuantity(2);
                $manager->persist($stock);
            }
        }

        // Création des utilisateurs 
        $admin = new User();
        $admin->setUsername('johnAdmin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($admin, '1111'))
            ->setEmail('johnAdmin@example.com')
            ->setVerified(true)
            ->setDeliveryAddress('123 Admin Street');
        $manager->persist($admin);

        $user = new User();
        $user->setUsername('johnUser')
            ->setRoles(['ROLE_USER'])
            ->setPassword($this->hasher->hashPassword($user, '1111'))
            ->setEmail('johnUser@example.com')
            ->setVerified(true)
            ->setDeliveryAddress('456 User Avenue');
        $manager->persist($user);

        // Sauvegarder tout dans la base de données
        $manager->flush();
    }
}
