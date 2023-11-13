<?php

namespace src\Views;

class View
{
    private $viewPath;
    private $viewData = [];

    public static function render($viewPath, array $params = [])
    {

        // Extract the path to the view file.
        $viewFilePath = view_path($viewPath . '.php');


        // replace dot with slash, if view path contains dot
        if (str_contains($viewPath, '.')) {
            $viewFilePath = view_path(str_replace('.', '/', $viewPath) . '.php');
        }

        // Check if the view file exists.

        if (!file_exists($viewFilePath)) {
            throw new ViewNotFoundException('View file does not exist');
        }


        // Extract params 
        extract($params);

        // Include the view file.
        include($viewFilePath);
    }

    public static function renderComponent($component, $data = [])
    {
        // Assume components are stored in a 'components' directory
        $componentPath = view_path($component . '.php');


        // replace dot with slash, if view path contains dot
        if (str_contains($component, '.')) {
            $componentPath = view_path(str_replace('.', '/', $component) . '.php');
        }

        // Check if the component file exists
        if (!file_exists($componentPath)) {
            echo "Component not found: $component";
        }

        // Extract the data to make it available within the component
        extract($data);

        // Include the component file
        include $componentPath;
    }
}
