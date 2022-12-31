<?php

namespace Sharpen\Versionna\Tests;

use PHPUnit\Framework\TestCase;
use Sharpen\Versionna\Storage\DatabaseConfiguration;

class DatabaseConfigurationTest extends TestCase
{
    /** @test */
    public function it_should_create_an_instance_of_database_configuration_from_dsn()
    {
        $configuration = DatabaseConfiguration::fromDsn('mysql:host=localhost;dbname=manticore');

        $this->assertInstanceOf(DatabaseConfiguration::class, $configuration);
    }
}
