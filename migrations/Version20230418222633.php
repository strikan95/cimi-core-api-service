<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230418222633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE amenity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_listing (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE listings_amenities (property_listing_id INT NOT NULL, amenity_id INT NOT NULL, INDEX IDX_5C069F0E61C0BD29 (property_listing_id), INDEX IDX_5C069F0E9F9F1305 (amenity_id), PRIMARY KEY(property_listing_id, amenity_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE listings_amenities ADD CONSTRAINT FK_5C069F0E61C0BD29 FOREIGN KEY (property_listing_id) REFERENCES property_listing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listings_amenities ADD CONSTRAINT FK_5C069F0E9F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenity (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE listings_amenities DROP FOREIGN KEY FK_5C069F0E61C0BD29');
        $this->addSql('ALTER TABLE listings_amenities DROP FOREIGN KEY FK_5C069F0E9F9F1305');
        $this->addSql('DROP TABLE amenity');
        $this->addSql('DROP TABLE property_listing');
        $this->addSql('DROP TABLE listings_amenities');
    }
}
