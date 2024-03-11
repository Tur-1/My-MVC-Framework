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

        return new View($view,  $data);
    }
}
