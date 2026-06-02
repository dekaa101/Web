<?php

class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes[] = ['method' => 'GET', 'path' => $path, 'handler' => $handler];
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes[] = ['method' => 'POST', 'path' => $path, 'handler' => $handler];
    }

    public function dispatch(string $uri): void
    {
        $uri    = strtok($uri, '?');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            $params = $this->match($route['path'], $uri);
            if ($params !== null) {
                $result  = call_user_func_array($route['handler'], $params);
                $content = is_array($result) ? $result['content'] : $result;
                $title   = is_array($result) ? ($result['title'] ?? 'TechShop') : 'TechShop';
                $this->render($content, $title);
                return;
            }
        }

        http_response_code(404);
        $this->render('<div class="not-found"><h2>404 — Страница не найдена</h2><p>Запрошенная страница не существует.</p><a href="/" class="btn">На главную</a></div>');
    }

    private function match(string $routePath, string $uri): ?array
    {
        if (str_starts_with($routePath, '~')) {
            if (preg_match($routePath, $uri, $matches)) {
                array_shift($matches);
                return $matches;
            }
            return null;
        }

        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#u';

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches);
            return array_map('urldecode', $matches);
        }

        return null;
    }

    private function render(string $content, string $title = 'TechShop'): void
    {
        require __DIR__ . '/../views/layout.php';
    }
}