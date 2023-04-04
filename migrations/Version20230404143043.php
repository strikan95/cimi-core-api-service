<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230404143043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE property_amenity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_listing (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE listings_amenities (property_listing_id INT NOT NULL, property_amenity_id INT NOT NULL, INDEX IDX_5C069F0E61C0BD29 (property_listing_id), INDEX IDX_5C069F0E2A3E3336 (property_amenity_id), PRIMARY KEY(property_listing_id, property_amenity_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, auth_identifier VARCHAR(180) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649692B8EDE (auth_identifier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE listings_amenities ADD CONSTRAINT FK_5C069F0E61C0BD29 FOREIGN KEY (property_listing_id) REFERENCES property_listing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listings_amenities ADD CONSTRAINT FK_5C069F0E2A3E3336 FOREIGN KEY (property_amenity_id) REFERENCES property_amenity (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE listings_amenities DROP FOREIGN KEY FK_5C069F0E61C0BD29');
        $this->addSql('ALTER TABLE listings_amenities DROP FOREIGN KEY FK_5C069F0E2A3E3336');
        $this->addSql('DROP TABLE property_amenity');
        $this->addSql('DROP TABLE property_listing');
        $this->addSql('DROP TABLE listings_amenities');
        $this->addSql('DROP TABLE user');
    }
}
