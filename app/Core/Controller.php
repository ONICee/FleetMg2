<?php
namespace Core;

abstract class Controller
{
    protected Router $router;
    protected Session $session;
    protected Auth $auth;

    public function __construct(Router $router, Session $session, Auth $auth)
    {
        $this->router = $router;
        $this->session = $session;
        $this->auth = $auth;
    }

    protected function view(string $template, array $data = [], string $layout = 'master')
    {
        View::render($template, $data, $layout);
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}