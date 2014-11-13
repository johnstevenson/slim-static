#SlimStatic

Slim PHP static proxy library.

## Contents
* [About](#About)
* [Usage](#Usage)
* [API](#Api)
* [Customizing](#Custom)
* [License](#License)

<a name="About"></a>
## About

SlimStatic provides a simple static interface to various features in the [Slim][slim]
micro framework. Turn this:

```php
$app->get('/hello-world', function()
{
	$app = Slim::getInstance();

	$app->view()->display('hello.html', array(
        'name' => $app->request()->get('name', 'world')
    ));
});

$app->run();
```

into this:

```php
Route::get('/hello-world', function()
{
	View::display('hello.html', array(
        'name' => Input::get('name', 'world')
    ));
});

App::run();
```

This library is based on [Slim-Facades][slim-facades] from Miroslav Rigler, but uses
[Statical][statical] to provide the static proxy interface.

<a name="Usage"></a>
## Usage
Install via [composer][composer]

```
composer require statical/slim-static
```

Create your Slim app and boot SlimStatic:

```php
use Slim\Slim;
use Statical\SlimStatic\SlimStatic;

$app = new Slim();

SlimStatic::boot($app);
```

Now you can start using the static proxies listed below. In addition there is a proxy to
[Statical][statical] itself, aliased as `Statical` and available in any namespace, so you
can easily use the library to add your own proxies (see [Customizing](#Custom)) or define
namespaces.

If your app is namespaced you can avoid syntax like `\App::method` or *use* statements
by employing the namespacing feature:

```php
# Allow any registered proxy to be called anywhere in the `App\Name` namespace

Statical::addNamespace('*', 'App\\Name\\*');
```

<a name="Api"></a>
## API

The following static proxies are available:

Statical Alias          | Proxy
----------------------- | ----------------------------------------
[App](#App)             | to Slim instance
[Config](#Config)       | calling the Slim config method
[Container](#Container) | to Slim container instance
[Input](#Input)         | to Slim\Http\Request instance
[Log](#Log)             | to Slim\Log instance
[Request](#Request)     | to Slim\Http\Request instance
[Response](#Response)   | to Slim\Http\Response instance
[Route](#Route)         | calling Slim route-matching methods
[View](#View)           | to Slim\View instance

<a name="App"></a>
#### App
Proxy to the Slim instance. Note that you cannot use the built-in resource locator statically,
because `App::foo = 'bar'` is not a method call. Use the [Container](#Container) proxy instead.

```php
App::expires('+1 week');
App::halt();
```

<a name="Config"></a>
#### Config
Sugar for Slim config, using the following methods:

- `get($key)` - returns value of `$app->config($key)`
- `set($key, $value = null)` - calls `$app->config($key, $value)`

```php
$debug = Config::get('debug');
Config::set('log.enable', true);

# Note that you could also use:
$debug = App::config('debug');
App::config('log.enable', true);
```

<a name="Container"></a>
#### Container
Proxy to the Slim container instance. Use this to access the built-in resource locator.

```php
# $app->foo = 'bar'
Container::set('foo', 'bar');

# $bar = $app->foo
$bar = Container::get('foo');

Container::singleton('log', function () {...});
$rawClosure = Container::protect(function () {...});
```

<a name="Input"></a>
#### Input
Proxy to the Slim\Http\Request instance with an additional method:

- `file($name)` - returns `$_FILES[$name]`, or null if the file was not sent in the request

```php
$avatar = Input::file('avatar');
$username = Input::get('username', 'default');
$password = Input::post('password');
```

<a name="Log"></a>
#### Log
Proxy to the Slim\Log instance.

```php
Log::info('My info');
Log::debug('Degug info');
```

<a name="Request"></a>
#### Request
Proxy to the Slim\Http\Request instance.

```php
$path = Request::getPath();
$xhr = Request::isAjax();
```

<a name="Response"></a>
#### Response
Proxy to the Slim\Http\Response instance.

```php
Response::redirect('/success');
Response::headers->set('Content-Type', 'application/json');
```

<a name="Route"></a>
#### Route
Sugar for the following Slim instance route-mapping methods:

- `map`, `get`, `post`, `put`, `patch`, `delete`, `options`, `group`, `any`, `urlFor`

```php
Route::get('/users/:id', function ($id) {...});
Route::post('/users',  function () {...});
Route:urlFor('admin');
```

Note that because these call the Slim instance you can also invoke them with `App::get`,
`App::post` etc.

<a name="View"></a>
#### View
Proxy to the Slim\View instance

```php
View::display('hello.html');
$output = View::render('world.html');
```

<a name="Custom"></a>
## Customizing
Since [Statical][statical] is already loaded, you can use it to create your own static proxies.
Let's take a `PaymentService` class as an example, that you want to alias as `Payment`.

The first step is to create a proxy class that extends the `Statical\BaseProxy` class.
It is normally empty and you can name it whatever you wish:

```php
class PaymentProxy extends \Statical\BaseProxy {}
```

You must then register this with Statical, using `addProxyInstance` if you use a class instance,
or `addProxyService` if you want to use the Slim container.
Using a class instance:

```php
# create our PaymentService class
$instance = new \PaymentService();

$alias = 'Payment';             # The static alias to call
$proxy = 'PaymentProxy';        # The proxy class you just created

Statical::addProxyInstance($alias, $proxy, $instance);

# Now we can call PaymentService methods via the static alias Payment
Payment::process();
```

Using the Slim container:

```php
# Register our service with Slim's DI container
Container::set('payment', function () {
    return new \PaymentService();
});


$alias = 'Payment';             # The static alias to call
$proxy = 'PaymentProxy';        # The proxy class you just created
$id = 'payment';                # The id of our service in the Slim container

Statical::addProxyService($alias, $proxy, Container::getInstance(), $id);

# Now we can call PaymentService methods via the static alias Payment
Payment::process();
```


<a name="License"></a>
## License

SlimStatic is licensed under the MIT License - see the `LICENSE` file for details


  [slim]: https://github.com/codeguy/slim
  [slim-facades]: https://github.com/itsgoingd/slim-facades
  [statical]: https://github.com/johnstevenson/statical
  [composer]: https://getcomposer.org
