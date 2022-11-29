<?php

namespace SiroDiaz\ManticoreMigration;

use DateTime;
use Exception;
use SiroDiaz\ManticoreMigration\Runner\Loader;

class MigrationCreator
{
    protected $migrationsPath;

    protected $name;

    protected $description;

    protected $createdAt;

    public function __construct(string $migrationsPath, string $name, string $description = '', DateTime $createdAt = null)
    {
        $this->migrationsPath = $migrationsPath;
        $this->name = $name;
        $this->description = $description;
        $this->createdAt = $createdAt ?? new DateTime();
    }

    public function setCreatedAt(DateTime $createdAt): MigrationCreator
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function generateMigrationFilename(): string
    {
        return $this->createdAt->format('Y_m_d_His') . '_' . $this->name . '.php';
    }

    public function getMigrationFullPath(): string
    {
        return $this->migrationsPath . DIRECTORY_SEPARATOR . $this->generateMigrationFilename();
    }

    protected function renderMigrationTemplate(): string
    {
        $migrationClassName = Loader::getMigrationClassName($this->getMigrationFullPath());

        return <<<PHP
<?php

use SiroDiaz\ManticoreMigration\Migration;

class {$migrationClassName} extends Migration
{
	public \$description = '{$this->description}';

	public function up(): void
	{
		// Your Sphinxql migration code goes here
		\$this->runner->execute('CREATE TABLE ...');
		// Optional: Your populate SQL query goes here
		\$this->indexer->index('SELECT * FROM ...');
	}

	public function down(): void
	{
		// Your undo migration code goes here
		\$this->runner->execute('DROP TABLE ...');
	}
}
PHP;
    }

    public function create(): bool
    {
        if (file_exists($this->getMigrationFullPath())) {
            throw new Exception('Migration already exists');
        }

        return boolval(file_put_contents($this->getMigrationFullPath(), $this->renderMigrationTemplate()));
    }

    protected function exists(): bool
    {
        file_exists($this->migrationsPath . DIRECTORY_SEPARATOR . $this->name . '.php');

        return true;
    }
}
