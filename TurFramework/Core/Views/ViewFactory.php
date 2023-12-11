<?php

namespace TurFramework\Core\Views;

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
     * Render the view file.
     */
    private function render()
    {
        // Start output buffering
        ob_start();

        // Extract data variables to be accessible in the view file
        extract($this->data, EXTR_SKIP);

        // Include the view file and capture its output
        include $this->viewPath;

        // Get the captured output and clean (end buffering)
        $renderedContent = ob_get_clean();

        return $renderedContent;
    }

    public function __destruct()
    {
        echo $this->render();
    }
}
