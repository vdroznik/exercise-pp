<?php

declare(strict_types=1);

namespace ExercisePromo\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231030165353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert promo sequence';
    }

    public function up(Schema $schema): void
    {
        $this->connection->insert('sequences', ['name' => 'promo', 'current' => 0]);
    }
}
