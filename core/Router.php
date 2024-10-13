<?php
// core/Router.php

class Router
{
    protected $routes = [];

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch($uri, $method)
    {
        if (array_key_exists($method, $this->routes)) {
            foreach ($this->routes[$method] as $route => $controller) {
                $pattern = $this->convertRouteToRegex($route);
                if (preg_match($pattern, $uri, $params)) {
                    // Remove the full match from the params array
                    array_shift($params);
                    // Extract only the captured groups
                    $params = array_filter($params, 'is_string', ARRAY_FILTER_USE_KEY);
                    $params = array_values($params);
                    return $this->callAction($controller, $params);
                }
            }
        }

        throw new Exception('No route defined for this URI.');
    }

    protected function convertRouteToRegex($route)
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z0-9-]+)', $route);
        return '/^' . $route . '$/i';
    }

    protected function callAction($controller, $params)
    {
        $parts = explode('@', $controller);
        $controllerName = $parts[0];
        $methodName = $parts[1];

        $controllerFile = '../controllers/' . $controllerName . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
        } else {
            throw new Exception("Controller file not found: {$controllerFile}");
        }

        if (!class_exists($controllerName)) {
            throw new Exception("Controller class not found: {$controllerName}");
        }

        $controllerInstance = new $controllerName();
        
        if (!method_exists($controllerInstance, $methodName)) {
            throw new Exception("{$controllerName} does not respond to the {$methodName} action.");
        }

        return call_user_func_array([$controllerInstance, $methodName], $params);
    }
}