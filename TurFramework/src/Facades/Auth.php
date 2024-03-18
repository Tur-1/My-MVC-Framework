<?php

namespace TurFramework\Facades;


/**

 * @method static \TurFramework\Auth\Authentication attempt(array $credentials = []) 
 * @method static \TurFramework\Auth\Authentication guard($name = null)
 * @method static \TurFramework\Auth\Authentication logout()
 * @method static \TurFramework\Auth\Authentication login($user)
 * @see \TurFramework\Auth\AuthManager
 * @see \TurFramework\Auth\AuthenticationGuard
 */
class Auth extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'auth';
    }
}
