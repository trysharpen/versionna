<?php

namespace SiroDiaz\ManticoreMigration\Runner;

use Manticoresearch\Client;

class ManticoreRunner implements Runner
{
	protected Client $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	public function execute(string $query): array
	{
		$queryDsl = [
			'mode' => 'raw',
			'body' => [
				'query' => $query,
			],
		];

		return $this->client->sql($queryDsl);
	}
}
