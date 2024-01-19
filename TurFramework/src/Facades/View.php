<?php

namespace TurFramework\Facades;


/**

 * @method static \TurFramework\Views\View make($view, array $data = [])
 * @method \TurFramework\Views\ViewFactory with($key, $value = null) 
 * @see \TurFramework\Views\View
 */
class View extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'view';
    }
}
