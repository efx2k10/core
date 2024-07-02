<?php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class {

    private const string TABLE_NAME = 'users';

    public function up(Schema $schema)
    {
        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true]);

        $table->addColumn('name', Types::STRING, ['length' => 255]);
        $table->addColumn('email', Types::STRING, ['length' => 255]);
        $table->addColumn('password', Types::STRING, ['length' => 255]);

        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema)
    {

    }
};