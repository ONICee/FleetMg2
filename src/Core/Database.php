<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use App\Config\Config;

class Database
{
    private static ?PDO $pdo = null;

    public static function get(): PDO
    {
        if (self::$pdo === null) {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4',
                Config::get('DB_HOST', 'localhost'),
                Config::get('DB_NAME', 'fleetrec')
            );
            self::$pdo = new PDO(
                $dsn,
                Config::get('DB_USER', 'root'),
                Config::get('DB_PASS', ''),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        }
        return self::$pdo;
    }
}