<?php

namespace TurFramework\Views;

use TurFramework\Views\ViewFactory;

class View
{

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
        // Return a new instance of View
        return new ViewFactory($view,  $data);
    }
}
