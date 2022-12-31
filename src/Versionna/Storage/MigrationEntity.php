<?php

namespace Sharpen\Versionna\Storage;

use DateTime;

/** @package Sharpen\Versionna */
class MigrationEntity
{
    protected string $name;

    protected int $version;

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

    /**
     *
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['migration_name'],
            $data['version'],
            $data['description'],
            new DateTime($data['created_at']),
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function generateCreatedAt(): void
    {
        $this->created_at = new DateTime();
    }

    /**
     *
     * @return array<string,string|int|null>
     */
    public function toArray(): array
    {
        return [
            'migration_name' => $this->name,
            'version' => $this->version,
            'description' => $this->description,
            'created_at' => $this->created_at->format('Y-m-d h:i:s'),
        ];
    }
}
