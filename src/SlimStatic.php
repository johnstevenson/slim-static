<?php
namespace Statical\SlimStatic;

class SlimStatic
{
    public static function boot(\Slim\Slim $slim)
    {
        // set Slim application for proxies
        SlimBase::$slim = $slim;

        // create a new Manager
        $manager = new \Statical\Manager();

        $base = 'Statical\\SlimStatic\\';

        // Add proxies that use the Slim instance
        $instances = array('App', 'Config');

        foreach ($instances as $alias) {
            $proxy = $base.$alias;
            $manager->addProxyInstance($alias, $proxy, $slim);
        }

        // Add Slim container
        $alias = 'Container';
        $proxy = $base.$alias;
        $manager->addProxyInstance($alias, $proxy, $slim->container);

        // Add services that are resolved out of the Slim container
        $container = array($slim, '__get');

        $services = array(
            'Input' => 'request',
            'Log' => 'log',
            'Request' => 'request',
            'Response' => 'response',
            'Route'    => 'router',
            'View'     => 'view',
        );

        foreach ($services as $alias => $id) {
            $proxy = $base.$alias;
            $manager->addProxyService($alias, $proxy, $container, $id);
        }

        return $manager;
    }
}
