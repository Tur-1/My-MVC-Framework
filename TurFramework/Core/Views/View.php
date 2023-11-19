<?php

namespace TurFramework\Core\Views;

class View
{
    public static function render($view, array $data = [])
    {
        $viewPath = self::getViewPath($view);

        // Check if view exists
        if (!file_exists($viewPath)) {
            throw new ViewNotFoundException($view);
        }

        // Extract data
        extract($data, EXTR_SKIP);

        // Include the view file.
        include $viewPath;
    }

    public static function getViewPath($view)
    {
        $viewPath = view_path($view);

        // replace dot with slash, if view path contains dot
        if (str_contains($view, '.')) {
            $viewPath = view_path(str_replace('.', '/', $view));
        }

        // Check if the view path is a directory
        if (is_dir($viewPath)) {
            // Define the path to index.php within the directory
            $viewPath = $viewPath.'/index';
        }

        // add .php to view path => index.php
        $viewPath .= '.php';

        return $viewPath;
    }
}
