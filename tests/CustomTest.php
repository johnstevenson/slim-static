<?php
namespace Statical\SlimStatic\Tests;

use Slim\Slim;
use Statical\SlimStatic\SlimStatic;

/**
 * @runTestsInSeparateProcesses
 */
class CustomTest extends \PHPUnit_Framework_TestCase
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

    public function testInstance()
    {
        $instance = new PaymentService();
        $alias = 'Payment';
        $proxy = __NAMESPACE__.'\\PaymentProxy';

        Statical::addProxyInstance($alias, $proxy, $instance);
        $this->assertTrue(Payment::process());
    }

    public function testService()
    {
        Container::set('payment', function () {
            return new PaymentService();
        });

        $alias = 'Payment';
        $proxy = __NAMESPACE__.'\\PaymentProxy';
        $id = 'payment';

        Statical::addProxyService($alias, $proxy, Container::getInstance(), $id);
        $this->assertTrue(Payment::process());
    }
}
