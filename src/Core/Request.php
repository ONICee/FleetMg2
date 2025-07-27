<?php
declare(strict_types=1);

namespace App\Core;

class Request
{
    public string $method;
    public string $path;
    public array $query;
    public array $body;
    public array $server;

    public static function capture(): self
    {
        $instance = new self();
        $instance->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $instance->path   = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/') ?: '/';
        $instance->query  = $_GET;
        $instance->body   = $_POST;
        $instance->server = $_SERVER;
        return $instance;
    }
}