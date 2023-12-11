<?php

namespace TurFramework\Core\Facades;

use TurFramework\Core\Http\HttpRequest;

/**
 * @method bool is(string $url)
 * @method mixed|null get(string $key, $default = null)
 * @method bool has(string $key)
 * @method bool isMethod(string $method)
 * @method string|null previousUrl()
 * @method array all()
 * @method bool isPost()
 * @method bool isGet()
 * @method string getMethod()
 * @method string|null previousUrlWithQuery()
 * @method string fullUrlWithQuery()
 * @method string fullUrl() 
 * @method string getPath()
 * @method array getValidMethods()
 * 
 * @see \TurFramework\Core\Http\HttpRequest
 */
class Request extends Facade
{

    protected static function getFacadeAccessor()
    {
        return new HttpRequest();
    }
}
