<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, callable|array $handler): void
    {
        $method = strtoupper($method);
        $this->routes[$method][$path] = $handler;
    }

    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function dispatch(Request $request): Response
    {
        $handler = $this->routes[$request->method][$request->path] ?? null;
        if ($handler === null) {
            return Response::html('<h1>404 Not Found</h1>', 404);
        }

        // Controller action pair
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            return $controller->$method($request);
        }

        // Callable closure
        return $handler($request);
    }
}