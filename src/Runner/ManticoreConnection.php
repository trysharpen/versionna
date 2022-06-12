<?php

namespace SiroDiaz\ManticoreMigration\Runner;

use ManticoreSearch\Client;

class ManticoreConnection
{
	private $configuration;

	/**
	 * @var Client
	 */
	private $client;

    public function __construct(array $configuration)
	{
		$this->configuration = $configuration;
		$this->client = new Client($configuration);
	}

	public function getClient(): Client
	{
		return $this->client;
	}

	public function getConfiguration(): array
	{
		return $this->configuration;
	}
}
