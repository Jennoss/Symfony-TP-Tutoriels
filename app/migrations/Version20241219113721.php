<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219113721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chapitre (id INT AUTO_INCREMENT NOT NULL, tutoriel_id INT NOT NULL, INDEX IDX_8C62B0257CB6CDBB (tutoriel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, chapitre_id INT NOT NULL, INDEX IDX_67F068BC1FBEEF7B (chapitre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tutoriel (id INT AUTO_INCREMENT NOT NULL, matiere_id INT NOT NULL, INDEX IDX_A2073AEDF46CD258 (matiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B0257CB6CDBB FOREIGN KEY (tutoriel_id) REFERENCES tutoriel (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC1FBEEF7B FOREIGN KEY (chapitre_id) REFERENCES chapitre (id)');
        $this->addSql('ALTER TABLE tutoriel ADD CONSTRAINT FK_A2073AEDF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B0257CB6CDBB');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC1FBEEF7B');
        $this->addSql('ALTER TABLE tutoriel DROP FOREIGN KEY FK_A2073AEDF46CD258');
        $this->addSql('DROP TABLE chapitre');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE tutoriel');
    }
}
