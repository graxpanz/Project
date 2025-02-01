<?php
class Route
{
    public static $routes = [];

    public static function get($uri, $controller)
    {
        self::$routes['GET'][$uri] = $controller;
    }

    public static function post($uri, $controller)
    {
        self::$routes['POST'][$uri] = $controller;
    }
    
    public static function getRoutes()
    {
        return self::$routes;
    }
}