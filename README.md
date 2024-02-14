<a name="section-1"></a>

# How to use

- [About](#section-1)
- [How to Install](#section-2)
- [Define Route](#section-3)
- [Route Parameters](#section-4)
- [Request](#section-5)
- [Dependency Injection](#section-6)
- [Constructor Dependency Injection](#section-7)
- [Binding Interface to Service Class](#section-8)
- [Register Custom Service Provider](#section-9)
- [Route Service Provider](#section-10)
- [Global Middleware](#section-11)
- [Route Middleware](#section-12)
- [Views](#section-13)
- [Multiple Database Connections](#section-14)
- [Models](#section-15) 
- [Form Request Validation](#section-16) 
- [Validation Rules](#section-17) 

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



use TurFramework\Facades\Route;
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



use TurFramework\Facades\Route;
use App\Http\Controllers\HomeController;

Route::post('/user/{id}', [HomeController::class, 'user'])->name('user');
```

Now accept this param in your controller like:

```php



namespace App\Http\Controllers;

use TurFramework\Http\Request;

class HomeController extends Controller
{

    public function user(Request $request, $id)
    {
        return $id;
    }
}
```

<a name="section-5"></a>

## Request

We can use Request in this application like

```php


namespace App\Http\Controllers;

use TurFramework\Http\Request;

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

<a name="section-6"></a>

## Dependency Injection

Now look at that, how you can use dependency injection.

```php



namespace App\Http\Controllers;

use TurFramework\Http\Request;

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

<a name="section-7"></a>

## Constructor Dependency Injection

Now look at that, how you can use dependency injection using constructor.

```php

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

<a name="section-8"></a>

## Binding Interface to Service Class

To bind an interface with a service class, you can use the `AppServiceProvider`,

### `app/Providers/AppServiceProvider.php`

```php
namespace App\Providers;

use App\Services\ExampleService;
use App\Services\ExampleServiceInterface;
use TurFramework\Application\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     */
    public function register()
    {
        /**
         * To bind an interface to a service class, use the following syntax:
         * $this->app->bind(interface::class, service::class);
         * This ensures that when the interface is requested, an instance of the service class is provided.
         */

        $this->app->bind(ExampleServiceInterface::class, ExampleService::class);
    }
}
```

<a name="section-9"></a>

## Register Custom Service Provider

You can register your own custom service providers to extend the functionality of the application. Here's how you can do it:

### `config/app.php`

In the `providers` array, add the namespace of your custom service provider.

```php
'providers' => [
    App\Providers\AppServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    // Add your custom service provider here
    App\Providers\CustomServiceProvider::class,
],


<?php

namespace App\Providers;

use TurFramework\Application\ServiceProvider;

class CustomServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     */
    public function register()
    {
        /**
         * Add your custom service registration logic here.
         * You can use $this->app->bind() or other methods to register services.
         * For example:
         * $this->app->bind(CustomServiceInterface::class, CustomService::class);
         */

         $this->app->bind(CustomServiceInterface::class, CustomService::class);
    }
}
```

<a name="section-10"></a>

## Route Service Provider

The `RouteServiceProvider` is responsible for loading route files into the application.

### `app/Providers/RouteServiceProvider.php`

In this service provider, you can use the `Route::group()` method to load route files. Here's an example:

```php

namespace App\Providers;

use TurFramework\Facades\Route;
use TurFramework\Application\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     */
    public function register()
    {
        /**
         * Use the Route::group() method to load route files.
         */

        // Load web routes from the 'app/routes/web.php' file.
        Route::group(base_path('app/routes/web.php'));

        // Load API routes from the 'app/routes/api.php' file.
        Route::group(base_path('app/routes/api.php'));
    }
}
```

<a name="section-11"></a>

## Global Middleware

In the application, global middleware runs during every request to your application.

```php
class Kernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        \App\Http\Middleware\ExampleMiddleware::class,
    ];

}
```

<a name="section-12"></a>

## Route Middleware

To define route middleware, update the App\Http\Kernel.php file's $routeMiddleware array as shown below:

```php
class Kernel
{
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Auth::class,
        'is_admin' => \App\Http\Middleware\IsAdmin::class,
    ];
}
```
```php
<?php

namespace App\Http\Middleware;

use TurFramework\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request)
    {
    }
}
```


And update your route like:

```php
<?php

use TurFramework\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index'])
       ->name('homePage')
       ->middleware('auth');

Route::get('/about', [AboutController::class, 'index'])
       ->name('aboutPage')
       ->middleware(['auth', 'is_admin']);


```

<a name="section-13"></a>

## Views
 
To render views ,use the global view helper like so

```php
 
 <?php
class HomeController extends Controller
{

    public function index(Request $request, ExampleServiceInterface $exampleService)
    {

        $users = User::query()->get();

        return view('pages.HomePage', ['users' => $users]);
    }
}
 ```

Views may also be returned using the View facade:

 ```php
 
use TurFramework\Facades\View;
 
return View::make('pages.HomePage', ['name'=> $name]);
 ```


<a name="section-14"></a>

 
## Multiple Database Connections
You can configure additional database connections by defining the connection details
 in the .env file and the config/database.php file.

.env file

```php
DB_CONNECTION=mysql_1
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mysql_database_1
DB_USERNAME=root
DB_PASSWORD=

DB_2_CONNECTION=mysql_2
DB_2_HOST=127.0.0.1
DB_2_PORT=3306
DB_2_DATABASE=mysql_database_2
DB_2_USERNAME=root
DB_2_PASSWORD=
 
```
config/database.php file
```php

     // Setting Default Connection
    'default' => env('DB_CONNECTION', 'mysql_1'),

    // database connections
    'connections' => [
        'mysql_1' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],
        'mysql_2' => [
            'driver' => 'mysql',
            'host' => env('DB_SEC_HOST', '127.0.0.1'),
            'port' => env('DB_SEC_PORT', '3306'),
            'database' => env('DB_SEC_DATABASE', 'forge'),
            'username' => env('DB_SEC_USERNAME', 'root'),
            'password' => env('DB_SEC_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],
    ],
```

## Accessing Database Connections Using Models
You can access different database connections using models by specifying the connection using the connection() method.

```php
 
Brand::connection('mysql_database_2')->get();

```
or you can set the default connection for a model using the $connection property

```php

class Brand extends Model
{
    protected $connection = 'mysql_database_2';
}



```
<a name="section-15"></a>

## Models
 
to Define a Model with Custom Table Name 

```php
 
 <?php
namespace App\Models;

use TurFramework\Database\Model;

class Brand extends Model
{
    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'brands';
}
?>

```

 
       
Limit Models:
```php
 Brand::query()->select('name', 'id')->get()
 ```  
       

get Models with Limit:
```php
 Brand::query()->limit(5)->get();
 ```        

Order Brands by Name (Descending):
```php
 Brand::query()->orderBy('name', 'desc')->get();
 ```     

        
get Models with Null Values for Name or Slug:
```php
Brand::query()->whereNull('name')->orWhereNull('slug')->get();
 ```
        

get Models with Not Null Values for Name or Slug:
```php
Brand::query()->whereNotNull('name')->orWhereNotNull('slug')->get();
 ```
     

Find Model by ID or Name:
```php
Brand::query()->where('id', $id)->orWhere('name', '!=', $name)->first();
 ```


Create a New Model:
```php
Brand::query()->create([
        'name' => $request->get('name'),
        'slug' => $request->get('slug'),
        ]);
 ```

Update an Existing Model:
```php
 Brand::query()->where('id', 2)
       ->update([
         'name' => $request->get('name'),
         'slug' => $request->get('slug'),
        ]);
 ```

 Delete a Model:
```php
Brand::query()->where('id', 2)->delete();
 ```


<a name="section-16"></a>

## Form Request Validation

To define form request validation, create a new class that extends `FormRequest`. You can specify the validation rules using either a string or an array format. Here's an example of how to define validation logic:

```php
<?php

namespace App\Http\Requests;

use TurFramework\Http\FormRequest;

class StoreUserRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required|min:6',
        ];
    }

    /**
     * Get the validation error messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'password.min' => 'The minimum password length is 6 characters',
        ];
   }
}    
```
<a name="section-17"></a>

## Validation Rules
Below is a list of all available validation rules and their function.
 

- **`unique:table,column`**:
Checks the value of the field is unique in a given database table
 ```php

// If the column option is not specified, the name of the field under validation will be used.

'email' => 'unique:users' 


// Instead of specifying the table name directly, you may specify the Eloquent model
// which should be used to determine the table name:
 
'email' => 'unique:App\Models\User,email_address' 


// Specifying a Custom Database Connection, you may prepend the connection name to the table name:

'email' => 'unique:connection.users,email_address' 

or

'email' => 'unique:connection.App\Models\User,email_address' 

```