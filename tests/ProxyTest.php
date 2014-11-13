<?php
namespace Statical\SlimStatic\Tests;

use Slim\Slim;
use Statical\SlimStatic\SlimStatic;

class ProxyTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    public function setUp()
    {
        $this->app = new Slim();
        Utils::setEnvironvment();

        SlimStatic::boot($this->app);

        // Allow any registered proxy in this namespace
        Statical::addNamespace('*', __NAMESPACE__.'\\*');
    }

    public function testApp()
    {
        $this->assertSame(App::getInstance(), $this->app);
    }

    public function testConfig()
    {
        $this->app->config('debug', true);
        $this->assertTrue(Config::get('debug'));
    }

    /**
    * Test Config fails when caled with an App method
    *
    * @expectedException BadMethodCallException
    *
    */
    public function testConfigFailsBadMethod()
    {
        Config::root();
    }

    public function testContainer()
    {
        $this->assertSame(Container::getInstance(), $this->app->container);
        Container::set('foo', 'bar');
        $this->assertSame('bar', $this->app->foo);
    }

    public function testInput()
    {
        $this->assertSame(Input::getInstance(), $this->app->request);
    }

    public function testLog()
    {
        $this->assertSame(Log::getInstance(), $this->app->log);
    }

    public function testRequest()
    {
        $this->assertSame(Request::getInstance(), $this->app->request);
    }

    public function testResponse()
    {
        $this->assertSame(\Response::getInstance(), $this->app->response);
    }

    public function testRoute()
    {
        $this->assertSame(Route::getInstance(), $this->app->router);
    }

    public function testView()
    {
        $this->assertSame(View::getInstance(), $this->app->view);
    }
}
