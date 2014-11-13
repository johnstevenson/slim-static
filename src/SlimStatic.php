<?php
namespace Statical\SlimStatic;

class SlimStatic
{
    /**
    * Boots up SlimStatic by registering its proxies with Statical.
    *
    * @param \Slim\Slim $slim
    * @return \Statical\Manager
    */
    public static function boot(\Slim\Slim $slim)
    {
        // set Slim application for syntactic-sugar proxies
        SlimSugar::$slim = $slim;

        // create a new Manager
        $manager = new \Statical\Manager();

        // Add proxies that use the Slim instance
        $aliases = array('App', 'Config', 'Route');
        static::addInstances($aliases, $manager, $slim);

        // Add special-case Slim container instance
        $aliases = array('Container');
        static::addInstances($aliases, $manager, $slim->container);

        // Add services that are resolved out of the Slim container
        $services = array(
            'Input' => 'request',
            'Log' => 'log',
            'Request' => 'request',
            'Response' => 'response',
            'View'     => 'view',
        );

        static::addServices($services, $manager, array($slim, '__get'));

        return $manager;
    }

    /**
    * Adds instances to the Statical Manager
    *
    * @param array $aliases
    * @param mixed $manager
    * @param object $instance
    */
    static protected function addInstances($aliases, $manager, $instance)
    {
        foreach ($aliases as $alias) {
            $proxy = __NAMESPACE__.'\\'.$alias;
            $manager->addProxyInstance($alias, $proxy, $instance);
        }
    }

    /**
    * Adds services to the Statical Manager
    *
    * @param array $services
    * @param mixed $manager
    * @param object $container
    */
    static protected function addServices($services, $manager, $container)
    {
        foreach ($services as $alias => $id) {
            $proxy = __NAMESPACE__.'\\'.$alias;
            $manager->addProxyService($alias, $proxy, $container, $id);
        }
    }
}
