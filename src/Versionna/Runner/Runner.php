<?php

namespace Sharpen\Versionna\Runner;

interface Runner
{
    /**
     *
     * @param string $query
     * @return array<mixed>
     */
    public function execute(string $query): array;
}
