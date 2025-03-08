<?php
namespace App\Core;

class Request
{
    public string $method;
    public string $uri;
    public array $headers;
    public array $query;
    public array $body;
    public array $params;

    public function __construct(string $method, string $uri, array $headers, array $query, array $body, array $params = [])
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->query = $query;
        $this->body = $body;
        $this->params = $params;
    }

    public function getHeader(string $name, $default = null)
    {
        $name = strtolower($name);
        foreach ($this->headers as $key => $value) {
            if (strtolower($key) === $name) {
                return $value;
            }
        }
        return $default;
    }

    public function getQuery(string $key, $default = null)
    {
        return $this->query[$key] ?? $default;
    }

    public function getBody(string $key, $default = null)
    {
        return $this->body[$key] ?? $default;
    }

    public function getParam(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    public function isMethod(string $method): bool
    {
        return strtoupper($this->method) === strtoupper($method);
    }
    public function getContentType(): ?string
    {
        return $this->getHeader('Content-Type');
    }

    public function isJson(): bool
    {
        return stripos($this->getContentType() ?? '', 'application/json') !== false;
    }
    public function getUser()
    {
        $userId = $this->user['user_id'];
        return \App\Models\User::find($userId);
    }
}