<?php
namespace Core;

class View
{
    public static function render(string $template, array $data = [], string $layout = 'master'): void
    {
        $templateFile = __DIR__ . '/../Views/' . $template . '.php';
        $layoutFile   = __DIR__ . '/../Views/layouts/' . $layout . '.php';

        if (!is_file($templateFile)) {
            http_response_code(404);
            echo 'View not found';
            return;
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include $templateFile;
        $content = ob_get_clean();

        if (is_file($layoutFile)) {
            include $layoutFile;
        } else {
            echo $content;
        }
    }
}