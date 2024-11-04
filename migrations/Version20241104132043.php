<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241104132043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration for creating sweat_shirt, stock, user, and messenger_messages tables with default data.';
    }

    public function up(Schema $schema): void
    {
        // Création des tables
        $this->addSql('CREATE TABLE sweat_shirt (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, thumbnail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, sweatshirt_id INT DEFAULT NULL, size VARCHAR(3) NOT NULL, quantity INT NOT NULL, INDEX IDX_4B365660A143AB7B (sweatshirt_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, delivery_address VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660A143AB7B FOREIGN KEY (sweatshirt_id) REFERENCES sweat_shirt (id)');

        // Insertion des données par défaut dans sweat_shirt
        $this->addSql("INSERT INTO sweat_shirt (name, price, thumbnail) VALUES ('Blackbelt', 29.90, 'Blackbelt.jpeg')");
        $this->addSql("INSERT INTO sweat_shirt (name, price, thumbnail) VALUES ('BlueBelt', 29.90, 'BlueBelt.jpeg')");
        $this->addSql("INSERT INTO sweat_shirt (name, price, thumbnail) VALUES ('Street', 34.50, 'Street.jpeg')");
        $this->addSql("INSERT INTO sweat_shirt (name, price, thumbnail) VALUES ('Pokeball', 45, 'Pokeball.jpeg')");
        $this->addSql("INSERT INTO sweat_shirt (name, price, thumbnail) VALUES ('PinkLady', 29.90, 'PinkLady.jpeg')");
        $this->addSql("INSERT INTO sweat_shirt (name, price, thumbnail) VALUES ('Snow', 32, 'Snow.jpeg')");
        $this->addSql("INSERT INTO sweat_shirt (name, price, thumbnail) VALUES ('Greyback', 28.50, 'Greyback.jpeg')");
        $this->addSql("INSERT INTO sweat_shirt (name, price, thumbnail) VALUES ('BlueCloud', 45, 'BlueCloud.jpeg')");
        $this->addSql("INSERT INTO sweat_shirt (name, price, thumbnail) VALUES ('BornInUsa', 59.90, 'BornInUsa.jpeg')");
        $this->addSql("INSERT INTO sweat_shirt (name, price, thumbnail) VALUES ('GreenSchool', 42.20, 'GreenSchool.jpeg')");

        // Insertion du stock pour chaque sweat_shirt et chaque taille avec une quantité de 2
        $sizes = ['XS', 'S', 'M', 'L', 'XL'];
        for ($sweatshirtId = 1; $sweatshirtId <= 10; $sweatshirtId++) {
            foreach ($sizes as $size) {
                $this->addSql("INSERT INTO stock (sweatshirt_id, size, quantity) VALUES ($sweatshirtId, '$size', 2)");
            }
        }

        // Insertion de deux utilisateurs
        $adminPassword = password_hash('1111', PASSWORD_BCRYPT);
        $userPassword = password_hash('1111', PASSWORD_BCRYPT);
        $this->addSql("INSERT INTO user (username, roles, password, email, is_verified, delivery_address) VALUES ('johnAdmin', '[\"ROLE_ADMIN\"]', '$adminPassword', 'johnAdmin@example.com', 1, '123 Admin Street')");
        $this->addSql("INSERT INTO user (username, roles, password, email, is_verified, delivery_address) VALUES ('johnUser', '[\"ROLE_USER\"]', '$userPassword', 'johnUser@example.com', 1, '456 User Avenue')");
    }

    public function down(Schema $schema): void
    {
        // Suppression des tables
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660A143AB7B');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE sweat_shirt');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
