<?php

namespace SiroDiaz\ManticoreMigration\Runner;

interface Runner
{
    /**
     *
     * @param string $query
     * @return array<mixed>
     */
    public function execute(string $query): array;
}
