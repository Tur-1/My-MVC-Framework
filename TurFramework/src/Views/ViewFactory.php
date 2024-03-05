<?php

namespace TurFramework\Views;

class ViewFactory
{

    /**
     * Create a new view instance.
     *
     * @param string $view 
     * @param array $data  
     * @throws ViewNotFoundException If the specified view file doesn't exist.
     * 
     * @return \TurFramework\Views\View
     */
    public function make($view, array $data = [])
    {
        // Return a new instance of View
        return new View($view,  $data);
    }
}
