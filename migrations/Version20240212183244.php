<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212183244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, architecture VARCHAR(100) NOT NULL, os VARCHAR(100) NOT NULL, status VARCHAR(100) NOT NULL, size NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tag (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, name VARCHAR(100) NOT NULL, tag_name VARCHAR(100) NOT NULL, status VARCHAR(50) NOT NULL, last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE tag');
    }
}
