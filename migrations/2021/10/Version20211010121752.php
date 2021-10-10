<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211010121752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C028CEA2');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C028CEA2 FOREIGN KEY (point_id) REFERENCES point (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C028CEA2');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C028CEA2 FOREIGN KEY (point_id) REFERENCES point (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
