<?php

namespace SiroDiaz\ManticoreMigration\Tests;

use DateTime;
use PHPUnit\Framework\TestCase;
use SiroDiaz\ManticoreMigration\MigrationCreator;

class MigrationCreatorTest extends TestCase
{
	/** @test */
	public function it_should_create_a_migration_creator_instance_successfully(): void
	{
		$creator = new MigrationCreator(sys_get_temp_dir(), 'CreateUserIndex', 'creates a new user rt index', new DateTime());
		$this->assertInstanceOf(MigrationCreator::class, $creator);

		$migrationName = $creator->generateMigrationFilename();
		$this->assertStringEndsWith('.php', $migrationName);

		$this->assertTrue($creator->create());

		unlink($creator->getMigrationFullPath());
	}

	/** @test */
	public function it_should_generate_a_valid_migration_filename()
	{
		$migrationCreationTimestamp = DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-22 12:45:20');
		$creator = new MigrationCreator(sys_get_temp_dir(), 'CreateUserIndex', 'creates a new user rt index', $migrationCreationTimestamp);

		$migrationName = $creator->generateMigrationFilename();
		$this->assertNotEmpty($migrationName);
		$this->assertEquals("{$migrationCreationTimestamp->format('Y_m_d_His')}_CreateUserIndex.php", $migrationName);
	}

	/** @test */
	public function it_should_generate_a_valid_migration_file()
	{
		$migrationCreationTimestamp = DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-22 12:45:20');
		$creator = new MigrationCreator(sys_get_temp_dir(), 'CreateUserIndex', 'creates a new user rt index', $migrationCreationTimestamp);

		$this->assertTrue($creator->create());

		unlink($creator->getMigrationFullPath());
	}
}
