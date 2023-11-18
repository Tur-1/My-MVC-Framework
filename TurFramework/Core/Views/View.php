<?php

namespace TurFramework\Core\Views;

class View
{
    public static function render($viewPath, array $data = [])
    {
        // Extract the path to the view file.
        $viewFilePath = view_path($viewPath.'.php');

        // replace dot with slash, if view path contains dot
        if (str_contains($viewPath, '.')) {
            $viewFilePath = view_path(str_replace('.', '/', $viewPath).'.php');
        }

        // Check if the view file exists.

        if (!file_exists($viewFilePath)) {
            throw new ViewNotFoundException($viewPath);
        }

        // Extract data
        extract($data, EXTR_SKIP);

        // Include the view file.

        include $viewFilePath;
    }

    public static function importComponent($component, $data = [])
    {
        // Assume components are stored in a 'components' directory
        $componentPath = view_path($component.'.php');

        // replace dot with slash, if view path contains dot
        if (str_contains($component, '.')) {
            $componentPath = view_path(str_replace('.', '/', $component).'.php');
        }

        // Check if the component file exists
        if (!file_exists($componentPath)) {
            throw new ViewNotFoundException($component);
        }

        // Extract the data to make it available within the component
        extract($data, EXTR_SKIP);

        // Include the component file

        include $componentPath;
    }
}
