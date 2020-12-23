<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201223151221 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add indexes on book_translation table';
    }

    public function up(Schema $schema) : void
    {
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE EXTENSION pg_trgm;');
        $this->addSql('CREATE INDEX bt_gin_idx ON book_translation using gin(lower(name) gin_trgm_ops)');
    }

    public function down(Schema $schema) : void
    {
        $this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP INDEX bt_gin_idx;');
        $this->addSql('DROP EXTENSION pg_trgm;');
    }
}
