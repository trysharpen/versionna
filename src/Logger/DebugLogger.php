<?php

namespace SiroDiaz\ManticoreMigration\Logger;


class DebugLogger
{
	private static $isEnabled = false;

	public static function enable()
	{
		self::$isEnabled = true;
	}

	public static function disable()
	{
		self::$isEnabled = false;
	}

	public function log($level, string $message, array $context = []): void
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
