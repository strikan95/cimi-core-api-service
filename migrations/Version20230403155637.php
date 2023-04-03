<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230403155637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD sub VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL, DROP email, DROP auth_identity');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649580282DC ON user (sub)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D649580282DC ON user');
        $this->addSql('ALTER TABLE user ADD email VARCHAR(128) NOT NULL, ADD auth_identity VARCHAR(255) DEFAULT NULL, DROP sub, DROP roles');
    }
}
