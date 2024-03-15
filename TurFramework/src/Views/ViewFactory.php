<?php

namespace TurFramework\views;

class ViewFactory
{

    /**
     * Create a new view instance.
     *
     * @param string $view 
     * @param array $data  
     * @throws ViewNotFoundException If the specified view file doesn't exist.
     * 
     * @return \TurFramework\views\View
     */
    public static function make($view, array $data = [])
    {

        return new View(static::getViewPath($view), $data);
    }

    public static function makeView($view, array $data = [])
    {
        return new View($view, $data);
    }

    /**
     * Get the path of the view file.
     *
     * @return string
     */
    private static function getViewPath($view)
    {
        $viewPath = view_path($view);

        // replace dot with slash, if view path contains dot
        if (str_contains($view, '.')) {
            $viewPath = view_path(str_replace('.', '/', $view));
        }

        // Check if the view path is a directory
        if (is_dir($viewPath)) {
            // Define the path to index.php within the directory
            $viewPath .= '/index';
        }

        // Add .php extension to view path (e.g., index.php)
        $viewPath .= '.php';


        return $viewPath;
    }
}
