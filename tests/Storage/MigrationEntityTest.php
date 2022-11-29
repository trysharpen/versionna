<?php

namespace SiroDiaz\ManticoreMigration\Tests\Storage;

use DateTime;
use PHPUnit\Framework\TestCase;
use SiroDiaz\ManticoreMigration\Storage\MigrationEntity;

class MigrationEntityTest extends TestCase
{
    /** @test */
    public function it_should_create_a_migration_entity_instance()
    {
        $entity = new MigrationEntity(
            'CreateUsersIndex',
            1,
            'Creates users index',
            DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-22 12:45:20')
        );

        $this->assertEquals(MigrationEntity::class, get_class($entity));
        $this->assertInstanceOf(MigrationEntity::class, $entity);
    }
}
