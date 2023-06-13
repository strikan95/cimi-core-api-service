<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613082605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE craue_geo_postalcode (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(2) NOT NULL, postal_code VARCHAR(20) NOT NULL, lat NUMERIC(9, 6) NOT NULL, lng NUMERIC(9, 6) NOT NULL, UNIQUE INDEX postal_code_idx (country, postal_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property_listing ADD lat VARCHAR(255) NOT NULL, ADD `long` VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE craue_geo_postalcode');
        $this->addSql('ALTER TABLE property_listing DROP lat, DROP `long`');
    }
}
