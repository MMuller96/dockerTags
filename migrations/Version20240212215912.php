<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212215912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD tag_id INT NOT NULL');
        $this->addSql('ALTER TABLE image ALTER architecture DROP NOT NULL');
        $this->addSql('ALTER TABLE image ALTER os DROP NOT NULL');
        $this->addSql('ALTER TABLE image ALTER status DROP NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C53D045FBAD26311 ON image (tag_id)');
        $this->addSql('ALTER TABLE tag ALTER status DROP NOT NULL');
        $this->addSql('ALTER TABLE tag ALTER last_updated TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE tag ALTER last_updated TYPE VARCHAR(100)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tag ALTER status SET NOT NULL');
        $this->addSql('ALTER TABLE tag ALTER last_updated TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE tag ALTER last_updated TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE image DROP CONSTRAINT FK_C53D045FBAD26311');
        $this->addSql('DROP INDEX IDX_C53D045FBAD26311');
        $this->addSql('ALTER TABLE image DROP tag_id');
        $this->addSql('ALTER TABLE image ALTER architecture SET NOT NULL');
        $this->addSql('ALTER TABLE image ALTER os SET NOT NULL');
        $this->addSql('ALTER TABLE image ALTER status SET NOT NULL');
    }
}
