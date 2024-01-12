<?php

namespace TurFramework\Facades;


/**

 * @method \TurFramework\Views\ViewFactory with($key, $value = null)
 * @method static \TurFramework\Views\ViewFactory make($view, array $data = [])
 * @see \TurFramework\Views\Factory
 */
class View extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'view';
    }
}
