<?php
namespace App\Core;

class Router
{
    private $routes = [];
    private $notFoundCallback;

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function set404($callback)
    {
        $this->notFoundCallback = $callback;
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes[$method] as $path => $callback) {
            if ($this->matchRoute($path, $uri)) {
                return $this->callAction($callback);
            }
        }

        if ($this->notFoundCallback) {
            call_user_func($this->notFoundCallback);
        }
    }

    private function matchRoute($route, $uri)
    {
        $route = rtrim($route, '/');
        $uri = rtrim($uri, '/');

        if ($route === $uri) {
            return true;
        }

        // Handle route parameters
        $routeParts = explode('/', $route);
        $uriParts = explode('/', $uri);

        if (count($routeParts) !== count($uriParts)) {
            return false;
        }

        foreach ($routeParts as $index => $part) {
            if (strpos($part, '{') === 0 && strpos($part, '}') === strlen($part) - 1) {
                continue;
            }
            if ($part !== $uriParts[$index]) {
                return false;
            }
        }

        return true;
    }

    private function callAction($callback)
    {
        if (is_callable($callback)) {
            return call_user_func($callback);
        }

        if (is_array($callback)) {
            [$controllerClass, $actionMethod] = $callback;
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass(); // Instantiate the controller
                if (method_exists($controller, $actionMethod)) {
                    return call_user_func([$controller, $actionMethod]); // Call the action method
                }
            }
        }

        throw new \RuntimeException('Invalid route callback');
    }
}