<?php
declare(strict_types=1);

namespace App\Core;

class View
{
    public static function render(string $template, array $params = []): string
    {
        $file = __DIR__ . '/../../views/' . $template . '.php';
        if (!file_exists($file)) {
            throw new \RuntimeException("View $template not found");
        }
        extract($params, EXTR_SKIP);
        ob_start();
        include $file;
        return ob_get_clean();
    }
}