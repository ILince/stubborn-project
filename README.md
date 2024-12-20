. PREREQUIS

Avant de commencer, assurez-vous d'avoir les éléments suivants installés :

- PHP (version 8.1 ou supérieure) : Symfony nécessite une version récente de PHP.

- Composer : l'outil de gestion des dépendances pour PHP.

- Symfony CLI (version 5.10.4 ou supérieure) : cela facilite la création et la gestion des projets Symfony. Vous pouvez l’installer via [Symfony CLI](https://symfony.com/download).



. INSTALLATION

Pour installer l'application sur votre machine, suivez ces étapes :

1.Clonez le dépôt :
https://github.com/ILince/stubborn-project

2.Installez les dépendances :
composer install

3.Configurez votre environnement : 
modifier le fichier ".env" et configurez le avec les paramètres de votre base de données.

4.Créez la base de données :
php bin/console doctrine:database:create

5.Appliquez les migrations :
php bin/console doctrine:migrations:migrate

6.Chargez les fixtures : :
php bin/console doctrine:fixtures:load




.  CONFIGURATION 

Après l'installation, vous pouvez configurer l'application en modifiant le fichier .env pour définir vos variables d'environnement, notamment les clés API Stripe.

Ajoutez vos clés API Stripe dans le fichier .env :

Configuration Stripe
STRIPE_PUBLIC_KEY=votre_cle_publique_stripe
STRIPE_SECRET_KEY=votre_cle_secrete_stripe




. TESTS

1.Configurez votre environnement test :
modifier le fichier ".env.test" et configurez le avec les paramètres de votre base de données test.

2.Créez la base de données test avec la commande :
php bin/console doctrine:database:create --env=test

5.Appliquez les migrations dans la base de données test :
php bin/console doctrine:migrations:migrate --env=test

3.Exécuter les tests, utilliser la commande: 
php bin/phpunit


. PS :
Les Utilisateurs ADMIN et USER sont créés en base de données apres avoir appliquer les migrations du fichier (Version20241104132043.php).

Info des utilisateurs :

Utilisateur ADMIN [ username : johnAdmin, password : 1111 ]
Utilisateur USER [ username : johnUser, password : 1111 ]


