<?php

namespace Efx\Core\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Efx\Core\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
    private string $name = 'migrate';

    private const TABLE_NAME = 'migrations';

    public function __construct(
        private Connection $connection,
        private string     $path
    )
    {
    }

    public function execute(array $params = []): int
    {
        if (array_key_exists('create', $params)) {
            $this->create($params);
            return 1;
        }

        //  $this->connection->setAutoCommit(false);

        // $this->connection->beginTransaction();

        try {
            $this->createMigrationsTable();

            $fromDb = $this->getExecutedMigrations();

            $fromFiles = $this->getMigrationFromFiles();

            $toDb = array_values(array_diff($fromFiles, $fromDb));

            $schema = new Schema();

            foreach ($toDb as $migration) {
                $instance = require $this->path . '/' . $migration;
                $instance->up($schema);
                echo "migration {$migration} try execute \n";
                $this->addMigration($migration);
            }


            $sqlArr = $schema->toSql($this->connection->getDatabasePlatform());

            if (isset($sqlArr[0])) {
                foreach ($sqlArr as $sql) {
                    $this->connection->executeQuery($sql);
                }

            } else {
                echo "new migrations not found\n";
            }


            //   $this->connection->commit();


        } catch (\Throwable $exception) {
            //  $this->connection->rollBack();
            throw $exception;
        }

        // $this->connection->setAutoCommit(false);
        return 0;
    }

    private function create($params)
    {
        $ts = time();
        $template = 'migration';

        if (array_key_exists('template', $params)) {
            switch ($params['template']) {
                case 'table':
                    $template = 'table';
            }
        }


        $str = file_get_contents(dirname(__FILE__) . '/MigrateCommandTemplate/' . $template . '.php');
        $name = $params['create'] ?? '';
        $str = str_replace('{table_name}', $name, $str);

        file_put_contents("{$this->path}/{$ts}_{$name}_{$template}.php", $str);

        echo "file for migration {$name} created from {$template} template\n";
    }

    private function createMigrationsTable(): int
    {
        $manager = $this->connection->createSchemaManager();

        if ($manager->tablesExist(self::TABLE_NAME)) return 0;

        $schema = new Schema();
        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'unsigned' => true]);
        $table->addColumn('migration', Types::STRING, ['length' => 255]);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, ['default' => 'CURRENT_TIMESTAMP']);
        $table->setPrimaryKey(['id']);

        $sqlArr = $schema->toSql($this->connection->getDatabasePlatform());

        $this->connection->executeQuery($sqlArr[0]);
        echo "main table [" . self::TABLE_NAME . "] created\n";

        return 1;
    }

    private function getExecutedMigrations(): array
    {
        $builder = $this->connection->createQueryBuilder();
        return $builder->select('migration')
            ->from(self::TABLE_NAME)
            ->executeQuery()
            ->fetchFirstColumn();
    }

    private function getMigrationFromFiles(): array
    {
        $files = scandir($this->path);
        $filesFiltered = array_filter($files, function ($file) {
            return !in_array($file, ['.', '..']);
        });

        return array_values($filesFiltered);
    }

    private function addMigration(string $migration): void
    {
        $this->connection->createQueryBuilder()
            ->insert(self::TABLE_NAME)
            ->values(['migration' => ':migration'])
            ->setParameter('migration', $migration)
            ->executeQuery();
    }
}