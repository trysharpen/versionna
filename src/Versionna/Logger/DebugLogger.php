<?php

namespace Sharpen\Versionna\Logger;

class DebugLogger
{
    private static bool $isEnabled = false;

    public static function enable(): void
    {
        self::$isEnabled = true;
    }

    public static function disable(): void
    {
        self::$isEnabled = false;
    }

    /**
     *
     * @param string $level
     * @param string $message
     * @param array<mixed> $context
     * @return void
     */
    public function log(string $level, string $message, array $context = []): void
    {
        if (self::$isEnabled) {
            $logMessage = [
                'level' => $level,
                'message' => $message,
                'time' => date('Y-m-d H:i:s'),
                'context' => $context,
            ];

            if (function_exists('dump')) {
                dump($logMessage);
            } else {
                var_dump($logMessage);
            }
        }
    }
}
