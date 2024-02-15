<?php

namespace TurFramework\Facades;


/**

 * @method static \TurFramework\Views\ViewFactory make($view, array $data = [])
 * @method \TurFramework\Views\View with($key, $value = null) 
 * @see \TurFramework\Views\View
 */
class View extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'view';
    }
}
