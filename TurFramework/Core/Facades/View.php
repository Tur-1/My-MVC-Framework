<?php

namespace TurFramework\Core\Facades;


/**

 * @method \TurFramework\Core\Views\ViewFactory with($key, $value = null)
 * @method static \TurFramework\Core\Views\ViewFactory make($view, array $data = [])
 * @see \TurFramework\Core\Views\Factory
 */
class View extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'view';
    }
}
