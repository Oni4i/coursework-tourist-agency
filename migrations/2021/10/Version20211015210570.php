<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211015210570 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating admin';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO user (point_id, username, roles, password, first_name, last_name) VALUES (?, ?, ?, ?, ?, ?)', [
            null,
            'admin',
            '["ROLE_ADMIN"]',
            '$2y$13$0WZ2h10bKuS6D3QPyLlBxuUumZMvSuhWOLMy99kywwolGjRWsvfgm',
            'Nikita',
            'Shipilov'
        ]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM user WHERE username = ?', [
            'admin'
        ]);
    }
}
