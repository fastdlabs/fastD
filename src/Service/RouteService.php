<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Service;


use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;

/**
 * Class RouteServiceProvider.
 */
class RouteService implements ServiceProviderInterface
{
    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function register(Container $container): void
    {
        $collection = new RouteCollection();
        $dispatcher = new RouteDispatcher($collection);
        $container->add('router', $collection);
        $container->add('dispatcher', $dispatcher);

        $routes = include app()->getPath() . '/src/config/routes.php';
    }
}
