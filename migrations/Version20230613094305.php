<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613094305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property_listing CHANGE lat lat DOUBLE PRECISION NOT NULL, CHANGE lon lon DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE university CHANGE lat lat DOUBLE PRECISION NOT NULL, CHANGE lon lon DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE university CHANGE lat lat VARCHAR(255) NOT NULL, CHANGE lon lon VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE property_listing CHANGE lat lat VARCHAR(255) NOT NULL, CHANGE lon lon VARCHAR(255) NOT NULL');
    }
}
