<?php

namespace SiroDiaz\ManticoreMigration\Runner;

interface Runner
{
    public function execute(string $query): array;
}
