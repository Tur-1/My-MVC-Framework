<a name="section-1"></a>
# How to use

- [About](#section-1)
- [How to Install](#section-2)
- [Define Route](#section-3)
- [Route Parameters](#section-4)
- [Dependency Injection](#section-5)
- [Constructor Dependency Injection](#section-6)
- [Request](#section-7)


<a name="section-1"></a>

## About Tur-Framework

Tur-Framework, A basic PHP MVC framework design in a way that you feel like you are working in a Laravel application.
 In this framework you will get all the basic features of a web application needs like routing, middleware, dependency injection, eloquent relationship, model.


<a name="section-2"></a>

## How to Install

We can easily setup and install this application with some few steps. Before using this application, minimum PHP 8.2 version is needed.

- Step 1: git clone https://github.com/Tur-1/Tur-Framework.git or download this application
- Step 2: Go to project directory with this command cd Tur-Framework and run composer update
- Step 3: Copy .env.example to .env
- Step 4: Start the development server by running this command php -S localhost:8000


<a name="section-3"></a>

## Define Route
To define route, navigate to this file and update
### `app/routes/web.php`
```php

<?php

use TurFramework\Core\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('homePage');
Route::controller(HomeController::class)->group(function () {
      Route::post('/user/{id}', 'user')->name('user');
});

Route::get('/about', [AboutController::class, 'about'])->name('aboutPage');

```
<a name="section-4"></a>

## Route Parameters
You can pass single or multiple parameter with route as like below
```php

<?php

use TurFramework\Core\Facades\Route;
use App\Http\Controllers\HomeController;

Route::post('/user/{id}', [HomeController::class, 'user'])->name('user');
```

Now accept this param in your controller like:

```php

<?php

namespace App\Http\Controllers;

use TurFramework\Core\Http\Request;

class HomeController extends Controller
{

    public function user(Request $request, $id)
    {
        return $id;
    }
}
```



<a name="section-5"></a>

## Dependency Injection
Now look at that, how you can use dependency injection.
```php

<?php

namespace App\Http\Controllers;

use TurFramework\Core\Http\Request;

class HomeController extends Controller
{   
    /**
     * You can pass as many class as you want as parameter
     */
    public function index(
        Request $request, //class dependency injection
    ) {
        
       return view('pages.HomePage');
    }

}
```

<a name="section-6"></a>

## Constructor Dependency Injection
Now look at that, how you can use dependency injection using constructor.
```php

<?php

namespace App\Http\Controllers;

use App\Services\ExampleService;

class HomeController extends Controller
{   
    public $exampleService;
    
    public function __construct(ExampleService $exampleService) 
    {
       $this->exampleService = $exampleService;
    }
}
```


<a name="section-6"></a>

## Request
We can use Request in this application like
```php

<?php

namespace App\Http\Controllers;

use TurFramework\Core\Http\Request;

class HomeController extends Controller
{

     public function index(Request $request)
    {
        // Assume we have a URL like http://www.example.com/?name=mahedi. Now let's check if the 'name' parameter exists in the request.
        if ($request->has('name')) {
        }

        //Now get the value from request like:
        $name = $request->get('name');
        $email = $request->get('email');

        // Get the URL of the previous page (if any) from the request.
        $previousUrl = $request->previousUrl();

        // Collect all input data from the request.
        $input = $request->all();
    }
}
```
