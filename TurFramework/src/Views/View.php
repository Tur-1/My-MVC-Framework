<?php

namespace TurFramework\views;

use TurFramework\Exceptions\ExceptionHandler;

class View
{

    private $data = [];
    private $viewPath;

    public function __construct($viewPath, $data)
    {
        $this->data = $data;
        $this->viewPath = $viewPath;
    }

    /**
     * Add a piece of data to the view.
     *
     * @param  string|array  $key
     * @param  mixed  $value
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
     * Render the view file.
     */
    private function render()
    {

        if (!file_exists($this->viewPath)) {
            throw ViewException::notFound($this->viewPath);
        }

        extract($this->data, EXTR_SKIP);

        ob_start();
        include $this->viewPath;
        $renderedView = ob_get_clean();

        echo $renderedView;
    }


    public function __destruct()
    {
        $this->render();
    }
}
