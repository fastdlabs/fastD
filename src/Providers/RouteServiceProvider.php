<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Providers;


use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;

/**
 * Class RouteServiceProvider.
 */
class RouteServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function register(Container $container): void
    {
        $collector = new RouteCollection(config()->get('namespace', '\\Controller\\'));
        $dispatcher = new RouteDispatcher($collector, config()->get('middleware', []));

        $container->add('router', $collector);
        $container->add('dispatcher', $dispatcher);

        include app()->getPath().'/config/routes.php';
    }
}
