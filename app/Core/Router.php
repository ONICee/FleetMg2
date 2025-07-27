<?php
namespace Core;

class Router
{
    private array $routes = [];
    private string $baseUrl;

    public function __construct(string $baseUrl = '/')
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function add(string $method, string $pattern, callable|array $handler): void
    {
        $method = strtoupper($method);
        $pattern = '#^' . $this->baseUrl . $pattern . '$#';
        $this->routes[$method][$pattern] = $handler;
    }

    public function get(string $pattern, callable|array $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable|array $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        foreach ($this->routes[$method] ?? [] as $pattern => $handler) {
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // remove full match
                return $this->invoke($handler, $matches);
            }
        }
        http_response_code(404);
        echo 'Page not found';
    }

    private function invoke(callable|array $handler, array $params): void
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            if (!class_exists($class)) {
                throw new \RuntimeException("Controller {$class} not found");
            }
            $controller = new $class($this, new Session(), new Auth(new Session(), Model::connection()));
            if (!method_exists($controller, $method)) {
                throw new \RuntimeException("Method {$method} not found in controller {$class}");
            }
            call_user_func_array([$controller, $method], $params);
        } else {
            call_user_func_array($handler, $params);
        }
    }
}