<?php

declare(strict_types=1);

class Request
{
    public string $method;
    public string $uri;
    public array $query;
    public array $body;
    public array $headers;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $rawUri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->uri = parse_url($rawUri, PHP_URL_PATH) ?: '/';
        $this->query = $_GET;
        $this->body = $this->parseBody();
        $this->headers = function_exists('getallheaders') ? getallheaders() : [];
    }

    private function parseBody(): array
    {
        $input = file_get_contents('php://input');
        if (!$input) {
            return [];
        }

        $data = json_decode($input, true);
        return is_array($data) ? $data : [];
    }
}
