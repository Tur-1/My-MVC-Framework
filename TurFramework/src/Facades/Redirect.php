<?php

namespace TurFramework\src\Facades;


/**

 * @method \TurFramework\src\Http\RedirectResponse with($key, $value = null)
 * @method \TurFramework\src\Http\RedirectResponse withInput(array $input)
 * @method \TurFramework\src\Http\RedirectResponse withErrors(array $errors)
 * @method \TurFramework\src\Http\RedirectResponse back()
 * @method \TurFramework\src\Http\RedirectResponse to(string $url, $status = 302)
 * @see \TurFramework\src\Router\Redirector
 */
class Redirect extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'redirect';
    }
}
