<?php
declare(strict_types=1);

namespace App\Core;

class Response
{
    public function __construct(
        private string $body = '',
        private int $status = 200,
        private array $headers = []
    ) {}

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }
        echo $this->body;
    }

    public static function html(string $content, int $status = 200): self
    {
        return new self($content, $status, ['Content-Type' => 'text/html; charset=utf-8']);
    }

    public static function text(string $content, int $status = 200): self
    {
        return new self($content, $status, ['Content-Type' => 'text/plain; charset=utf-8']);
    }
}