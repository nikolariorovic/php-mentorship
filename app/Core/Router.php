<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private $notFoundHandler;
    private array $currentGroupMiddleware = [];
    private string $currentGroupPrefix = '';

    public function get(string $uri, $handler, $middleware = [])
    {
        $this->addRoute('GET', $uri, $handler, $middleware);
    }

    public function post(string $uri, $handler, $middleware = [])
    {
        $this->addRoute('POST', $uri, $handler, $middleware);
    }

    public function patch(string $uri, $handler, $middleware = [])
    {
        $this->addRoute('PATCH', $uri, $handler, $middleware);
    }

    public function delete(string $uri, $handler, $middleware = [])
    {
        $this->addRoute('DELETE', $uri, $handler, $middleware);
    }

    private function addRoute(string $method, string $uri, $handler, $middleware = [])
    {
        $middleware = array_merge($this->currentGroupMiddleware, $middleware);
        $uri = $this->currentGroupPrefix . $uri;

        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = rtrim($uri, '/');
        }
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function group(array $options, \Closure $callback)
    {
        $parentMiddleware = $this->currentGroupMiddleware;
        $parentPrefix = $this->currentGroupPrefix;

        $this->currentGroupMiddleware = array_merge($parentMiddleware, $options['middleware'] ?? []);
        $this->currentGroupPrefix = $parentPrefix . ($options['prefix'] ?? '');

        $callback($this);

        $this->currentGroupMiddleware = $parentMiddleware;
        $this->currentGroupPrefix = $parentPrefix;
    }

    public function setNotFoundHandler($handler)
    {
        $this->notFoundHandler = $handler;
    }

    public function dispatch()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if ($requestUri !== '/' && substr($requestUri, -1) === '/') {
            $requestUri = rtrim($requestUri, '/');
        }

        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            $allowedMethods = ['PUT', 'PATCH', 'DELETE'];
            $methodFromForm = strtoupper($_POST['_method']);
            if (in_array($methodFromForm, $allowedMethods, true)) {
                $requestMethod = $methodFromForm;
            }
        }

        foreach ($this->routes as $route) {
            $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([a-zA-Z0-9_-]+)', $route['uri']);
            $pattern = "#^" . $pattern . "$#";

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);

                foreach ($route['middleware'] as $middleware) {
                    $result = (new $middleware())->handle();
                    if ($result === false) {
                        return;
                    }
                }

                if (is_array($route['handler'])) {
                    [$class, $method] = $route['handler'];
                    $controller = new $class();
                    return call_user_func_array([$controller, $method], $matches);
                } else {
                    return call_user_func_array($route['handler'], $matches);
                }
            }
        }

        if ($this->notFoundHandler) {
            call_user_func($this->notFoundHandler);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
} 