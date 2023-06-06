<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230606130952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE amenity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, user_identifier VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, UNIQUE INDEX UNIQ_88BDF3E9D0494586 (user_identifier), UNIQUE INDEX UNIQ_88BDF3E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_listing (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, INDEX IDX_92CD02AC7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE listings_amenities (property_listing_id INT NOT NULL, amenity_id INT NOT NULL, INDEX IDX_5C069F0E61C0BD29 (property_listing_id), INDEX IDX_5C069F0E9F9F1305 (amenity_id), PRIMARY KEY(property_listing_id, amenity_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property_listing ADD CONSTRAINT FK_92CD02AC7E3C61F9 FOREIGN KEY (owner_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE listings_amenities ADD CONSTRAINT FK_5C069F0E61C0BD29 FOREIGN KEY (property_listing_id) REFERENCES property_listing (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listings_amenities ADD CONSTRAINT FK_5C069F0E9F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenity (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property_listing DROP FOREIGN KEY FK_92CD02AC7E3C61F9');
        $this->addSql('ALTER TABLE listings_amenities DROP FOREIGN KEY FK_5C069F0E61C0BD29');
        $this->addSql('ALTER TABLE listings_amenities DROP FOREIGN KEY FK_5C069F0E9F9F1305');
        $this->addSql('DROP TABLE amenity');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE property_listing');
        $this->addSql('DROP TABLE listings_amenities');
    }
}
