<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
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

    public function get($path, $callback, $middleware = [])
    {
        return parent::get($path, $this->concat($callback), $middleware);
    }

    public function post($path, $callback, $middleware = [])
    {
        return parent::post($path, $this->concat($callback), $middleware);
    }

    public function patch($path, $callback, $middleware = [])
    {
        return parent::patch($path, $this->concat($callback), $middleware);
    }

    public function put($path, $callback, $middleware = [])
    {
        return parent::put($path, $this->concat($callback), $middleware);
    }

    public function delete($path, $callback, $middleware = [])
    {
        return parent::delete($path, $this->concat($callback), $middleware);
    }

    public function head($path, $callback, $middleware = [])
    {
        return parent::head($path, $this->concat($callback), $middleware);
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
