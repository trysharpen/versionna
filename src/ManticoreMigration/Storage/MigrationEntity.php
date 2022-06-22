<?php

namespace SiroDiaz\ManticoreMigration\Storage;

use DateTime;

/** @package SiroDiaz\ManticoreMigration */
class MigrationEntity
{
    /**
     * @var string
     */
    protected $name;

	/**
	 * @var int
	 */
	protected $version;

    /**
     *
     * @var string|null
     */
    protected $description;

    /**
     * @var DateTime|null
     */
    protected $created_at;

    public function __construct(string $name, int $version, string $description = null, DateTime $created_at = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->version = $version;
        $this->created_at = $created_at;
    }

	public static function fromArray(array $data): static
	{
		return new static(
			$data['migration_name'],
			$data['version'],
			$data['description'],
			new DateTime($data['created_at']),
		);
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return int
	 */
	public function getVersion(): int
	{
		return $this->version;
	}

	/**
	 * @return string|null
	 */
	public function getDescription(): ?string
	{
		return $this->description;
	}

	/**
	 * @return DateTime|null
	 */
	public function getCreatedAt(): ?DateTime
	{
		return $this->created_at;
	}

	public function generateCreatedAt(): void
	{
		$this->created_at = new DateTime();
	}

	public function toArray()
	{
		return [
			'migration_name' => $this->name,
			'version' => $this->version,
			'description' => $this->description,
			'created_at' => $this->created_at->format('Y-m-d h:i:s'),
		];
	}
}
