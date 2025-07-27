<?php
declare(strict_types=1);

namespace App\Config;

use Dotenv\Dotenv;

class Config
{
    private static bool $loaded = false;

    public static function loadEnv(string $root): void
    {
        if (self::$loaded) {
            return;
        }
        if (file_exists($root . '/.env')) {
            $dotenv = Dotenv::createImmutable($root);
            $dotenv->safeLoad();
        }
        self::$loaded = true;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
}