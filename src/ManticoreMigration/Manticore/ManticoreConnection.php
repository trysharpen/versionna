<?php

namespace SiroDiaz\ManticoreMigration\Manticore;

use Manticoresearch\Client;

class ManticoreConnection
{
	/**
	 * @var Client
	 */
	protected $client;

	/**
	 * @var string
	 */
	protected $host;

	/**
	 * @var string
	 */
	protected $port;

	public function __construct(string $host, string $port)
	{
		$this->host = $host;
		$this->port = $port;

		$this->createClient();
	}

	public function createClient()
	{
		$this->client = new Client([
			'host' => $this->host,
			'port' => $this->port,
		]);
	}

	public function getClient(): Client
	{
		return $this->client;
	}

	public function getHost(): string
	{
		return $this->host;
	}

	public function getPort(): string
	{
		return $this->port;
	}
}
