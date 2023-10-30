<?php

declare(strict_types=1);

namespace ExercisePromo\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231030081309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Promos storage';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('promos');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned' => true]);
        $table->setPrimaryKey(["id"]);
        $table->addColumn('code', 'string', ['length' => 10]);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('promos');
    }
}
