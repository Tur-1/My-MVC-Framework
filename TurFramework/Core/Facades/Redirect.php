<?php

namespace TurFramework\Core\Facades;

use TurFramework\Core\Router\Redirector;


/**

 * @method \TurFramework\Core\Http\RedirectResponse with($key, $value = null)
 * @method \TurFramework\Core\Http\RedirectResponse withInput(array $input)
 * @method \TurFramework\Core\Http\RedirectResponse withErrors(array $errors)
 * @method \TurFramework\Core\Http\RedirectResponse back()
 * @method \TurFramework\Core\Http\RedirectResponse to(string $url, $status = 302)
 * @see \TurFramework\Core\Router\Redirector
 */
class Redirect extends Facade
{

    protected static function getFacadeAccessor()
    {
        return new Redirector();
    }
}
