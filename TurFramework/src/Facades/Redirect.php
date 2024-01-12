<?php

namespace TurFramework\Facades;


/**

 * @method \TurFramework\Http\RedirectResponse with($key, $value = null)
 * @method \TurFramework\Http\RedirectResponse withInput(array $input)
 * @method \TurFramework\Http\RedirectResponse withErrors(array $errors)
 * @method \TurFramework\Http\RedirectResponse back()
 * @method \TurFramework\Http\RedirectResponse to(string $url, $status = 302)
 * @see \TurFramework\Router\Redirector
 */
class Redirect extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'redirect';
    }
}
