<?php

namespace SiroDiaz\ManticoreMigration\Runner;

use Manticoresearch\Client;

class ManticoreConnection
{
	/**
	 * @var array<string,mixed>
	 */
	private array $configuration;

	/**
	 * @var Client
	 */
	private $client;

	/**
	 *
	 * @param array<string,mixed> $configuration
	 * @return void
	 */
	public function __construct(array $configuration)
	{
		$this->configuration = $configuration;
		$this->client = new Client($configuration);
	}

	public function getClient(): Client
	{
		return $this->client;
	}

	/**
	 *
	 * @return array<string,mixed>
	 */
	public function getConfiguration(): array
	{
		return $this->configuration;
	}
}
