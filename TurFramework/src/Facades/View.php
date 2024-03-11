<?php

namespace TurFramework\Facades;


/**

 * @method static \TurFramework\views\ViewFactory make($view, array $data = [])
 * @method \TurFramework\views\View with($key, $value = null) 
 * 
 * @see \TurFramework\views\ViewFactory
 */
class View extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'view';
    }
}
