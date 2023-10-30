<?php

declare(strict_types=1);

namespace ExercisePromo\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231030164544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Sequence';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('sequences');
        $table->addColumn('name', 'string', ['length' => 16]);
        $table->setPrimaryKey(["name"]);
        $table->addColumn('current', 'integer', ['unsigned' => true]);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('sequences');
    }
}
