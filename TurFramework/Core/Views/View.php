<?php

namespace TurFramework\Core\Views;

class View
{
    protected $viewPath;
    protected $data = [];

    /**
     * Constructor to initialize the View class.
     */
    public function __construct($view, array $data = [])
    {
        $viewPath = $this->getViewPath($view);

        // Check if the view file exists
        if (!file_exists($viewPath)) {
            throw new ViewNotFoundException($view);
        }

        $this->viewPath = $viewPath;

        $this->data = array_merge($this->data, $data);
    }

    /**
     * Render the view file.
     */
    public function render()
    {
        // Extract data variables to be accessible in the view file
        extract($this->data, EXTR_SKIP);

        // Include the view file
        include $this->viewPath;
    }

    /**
     * Add a piece of data to the view.
     *
     * @param string|array $key
     *
     * @return $this
     */
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
     * Automatically render the view when the object is destroyed.
     */
    public function __destruct()
    {
        $this->render();
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
