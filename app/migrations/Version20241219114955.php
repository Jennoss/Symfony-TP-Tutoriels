<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219114955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapitre ADD title VARCHAR(255) NOT NULL, ADD content LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD content LONGTEXT NOT NULL, ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE matiere ADD name VARCHAR(255) NOT NULL, ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE tutoriel ADD title VARCHAR(255) NOT NULL, ADD content LONGTEXT NOT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapitre DROP title, DROP content');
        $this->addSql('ALTER TABLE commentaire DROP content, DROP created_at');
        $this->addSql('ALTER TABLE matiere DROP name, DROP description');
        $this->addSql('ALTER TABLE tutoriel DROP title, DROP content, DROP created_at, DROP updated_at');
    }
}
