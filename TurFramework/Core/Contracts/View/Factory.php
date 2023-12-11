<?php

namespace TurFramework\Core\Contracts\View;

interface Factory
{
    /**
     * Get the evaluated view contents for the given view.
     * @param  string  $view
     * @param array  $data
     * @return TurFramework\Core\Contracts\View\ViewInterface
     */
    public function make($view, $data = []);
}
