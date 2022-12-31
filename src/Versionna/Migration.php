<?php

declare(strict_types=1);

namespace Sharpen\Versionna;

use Sharpen\Versionna\Indexer\Indexer;
use Sharpen\Versionna\Runner\Runner;

abstract class Migration
{
    /**
     * @var Runner
     */
    protected $runner;

    /**
     * @var Indexer
     */
    protected $indexer;

    /**
     * @var string
     */
    public $description = '';

    public function __construct(Runner $runner, Indexer $indexer)
    {
        $this->runner = $runner;
        $this->indexer = $indexer;
    }

    abstract public function up(): void;

    abstract public function down(): void;

    public function __toString(): string
    {
        return get_class($this);
    }
}
