<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\ServiceProvider;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;

class Router extends RouteCollection
{
    protected function concat($callback)
    {
        if (is_string($callback)) {
            return '\\Controller\\'.$callback;
        }

        return $callback;
    }

    public function get($path, $callback, array $defaults = [])
    {
        return parent::get($path, $this->concat($callback), $defaults);
    }

    public function post($path, $callback, array $defaults = [])
    {
        return parent::post($path, $this->concat($callback), $defaults);
    }

    public function patch($path, $callback, array $defaults = [])
    {
        return parent::patch($path, $this->concat($callback), $defaults);
    }

    public function put($path, $callback, array $defaults = [])
    {
        return parent::put($path, $this->concat($callback), $defaults);
    }

    public function delete($path, $callback, array $defaults = [])
    {
        return parent::delete($path, $this->concat($callback), $defaults);
    }

    public function head($path, $callback, array $defaults = [])
    {
        return parent::head($path, $this->concat($callback), $defaults);
    }
}

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
    public function register(Container $container)
    {
        $router = new Router();
        $dispatcher = new RouteDispatcher($router, $container['config']->get('middleware', []));

        $container->add('router', $router);
        $container->add('dispatcher', $dispatcher);

        include app()->getPath().'/config/routes.php';
    }
}
