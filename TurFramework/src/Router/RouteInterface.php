<?php

namespace TurFramework\src\Router;

interface RouteInterface
{
    public static function addRoute($method, $route, $action);
    public static function get($route, $action);
    public static function post($route, $action);
    public function reslove();
    public function loadAllRoutesFiles();
}
