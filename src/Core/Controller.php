<?php
declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected function view(string $template, array $params = []): Response
    {
        return Response::html(View::render($template, $params));
    }
}