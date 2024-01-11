<?php

namespace TurFramework\src\Facades;


/**

 * @method \TurFramework\src\Views\ViewFactory with($key, $value = null)
 * @method static \TurFramework\src\Views\ViewFactory make($view, array $data = [])
 * @see \TurFramework\src\Views\Factory
 */
class View extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'view';
    }
}
