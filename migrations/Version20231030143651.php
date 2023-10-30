<?php

declare(strict_types=1);

namespace ExercisePromo\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231030143651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Promos issues';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('promos_issues');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned' => true]);
        $table->setPrimaryKey(["id"]);
        $table->addColumn('promo_id', 'integer', ['unsigned' => true]);
        $table->addForeignKeyConstraint('promos', ['promo_id'], ['id'], ["onDelete" => "RESTRICT"], 'fk_promos_issues_promo_id');
        $table->addUniqueConstraint(['promo_id'], 'uk_promos_issues_promo_id');
        $table->addColumn('ip', 'string', ['length' => 15]);
        $table->addColumn('created_at', 'datetime', ['notnull' => true])->setDefault('CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('promos_issues');
    }
}
