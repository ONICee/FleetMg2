<?php
namespace Core;

use PDO;

abstract class Model
{
    protected static PDO $pdo;

    public static function setConnection(PDO $pdo): void
    {
        self::$pdo = $pdo;
    }

    protected static function pdo(): PDO
    {
        return self::$pdo;
    }

    public static function connection(): PDO
    {
        return self::$pdo;
    }
}