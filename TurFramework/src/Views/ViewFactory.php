<?php

namespace TurFramework\Views;

class ViewFactory
{

    private $data = [];
    private $viewPath;

    public function __construct($viewPath, $data)
    {
        $this->data = $data;
        $this->viewPath = $viewPath;
    }

    public function with($key, $value = null)
    {
        // Merge data if an array is provided
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            // Set key-value pair in data array
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Get the path of the view file.
     *
     * @return string
     */
    private function getViewPath($view)
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
    /**
     * Render the view file.
     */
    private function render()
    {
        $view = $this->getViewPath($this->viewPath);

        // Check if the view file exists
        if (!file_exists($view)) {
            throw ViewException::notFound($this->viewPath);
        }

        ob_start();

        extract($this->data, EXTR_SKIP);

        include $view;

        return (string) ob_get_flush();
    }


    public function __destruct()
    {
        return $this->render();
    }
}
