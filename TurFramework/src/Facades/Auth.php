<?php

namespace TurFramework\Facades;


/**

 * @method static \TurFramework\Auth\Authentication attempt(array $credentials = []) 
 * @method static \TurFramework\Auth\Authentication guard($name = null)
 * @see \TurFramework\Auth\Authentication
 */
class Auth extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'auth';
    }
}
