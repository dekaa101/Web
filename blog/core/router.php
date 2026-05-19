<?php
 
class Router
{
    private array $routes = [];
 
    /** Регистрация GET-роута */
    public function get(string $path, callable $handler): void
    {
        $this->routes[] = ['path' => $path, 'handler' => $handler];
    }
 
    /** Обработка входящего запроса */
    public function dispatch(string $uri): void
    {
        // Убираем query string (?foo=bar)
        $uri = strtok($uri, '?');
 
        foreach ($this->routes as $route) {
            $params = $this->match($route['path'], $uri);
            if ($params !== null) {
                $result = call_user_func_array($route['handler'], $params);
                $content = is_array($result) ? $result['content'] : $result;
                $title   = is_array($result) ? ($result['title'] ?? 'Мой блог') : 'Мой блог';
                $this->render($content, $title);
                return;
            }
        }
 
        // 404
        http_response_code(404);
        $this->render('<h2>404 — Страница не найдена</h2><p>Запрошенная страница не существует.</p>');
    }
 
    /** Сравнивает шаблон роута с URI, возвращает массив параметров или null */
    private function match(string $routePath, string $uri): ?array
    {
        // Превращаем /hello/{name} → regex /hello/([^/]+)
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#u';
 
        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); // убираем полное совпадение
            return array_map('urldecode', $matches);
        }
 
        return null;
    }
 
    /** Оборачивает контент в общий HTML-шаблон */
    private function render(string $content, string $title = 'Мой блог'): void
    {
        require __DIR__ . '/../views/layout.php';
    }
}
 