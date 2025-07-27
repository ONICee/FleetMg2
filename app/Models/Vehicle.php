<?php
namespace Models;

use Core\Model;
use PDO;

class Vehicle extends Model
{
    public static function all(): array
    {
        $stmt = self::connection()->query('SELECT * FROM vehicles');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}