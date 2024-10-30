<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240916084909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock ADD sweatshirt_id INT DEFAULT NULL, ADD size VARCHAR(3) NOT NULL, ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660A143AB7B FOREIGN KEY (sweatshirt_id) REFERENCES sweat_shirt (id)');
        $this->addSql('CREATE INDEX IDX_4B365660A143AB7B ON stock (sweatshirt_id)');
        $this->addSql('ALTER TABLE sweat_shirt DROP stock');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660A143AB7B');
        $this->addSql('DROP INDEX IDX_4B365660A143AB7B ON stock');
        $this->addSql('ALTER TABLE stock DROP sweatshirt_id, DROP size, DROP quantity');
        $this->addSql('ALTER TABLE sweat_shirt ADD stock JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
