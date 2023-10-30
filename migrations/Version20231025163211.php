<?php

declare(strict_types=1);

namespace ExercisePromo\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231025163211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ips';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('ips');
        $table->addColumn('ipp', 'integer', ['unsigned' => true]);
        $table->setPrimaryKey(["ipp"]);
        $table->addColumn('cnt', 'integer', ['notnull' => true]);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('ips');
    }
}
