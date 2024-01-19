<?php

namespace TurFramework\Views;

use TurFramework\Views\ViewFactory;

class View
{
    public $viewPath;
    public $data = [];


    /**
     * Create a new view instance.
     *
     * @param string $view 
     * @param array $data  
     * @throws ViewNotFoundException If the specified view file doesn't exist.
     * @return \TurFramework\Views\ViewFactory
     */
    public function make($view, array $data = [])
    {
        $viewPath = $this->getViewPath($view);

        // Check if the view file exists
        if (!file_exists($viewPath)) {
            throw new ViewNotFoundException($view);
        }

        $this->viewPath = $viewPath;

        $this->data = array_merge($this->data, $data);

        // Return a new instance of View
        return new ViewFactory($this->viewPath,  $this->data);
    }


    /**
     * Get the path of the view file.
     *
     * @return string
     */
    public function getViewPath($view)
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
