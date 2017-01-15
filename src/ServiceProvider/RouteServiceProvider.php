<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
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
            return '\\Http\\Controller\\' . $callback;
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

    public function delete($path, $callback, array $defaults = [])
    {
        return parent::delete($path, $this->concat($callback), $defaults);
    }

    public function head($path, $callback, array $defaults = [])
    {
        return parent::head($path, $this->concat($callback), $defaults);
    }
}

class RouteServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $router = new Router();
        $dispatcher = new RouteDispatcher($router, $container['config']->get('middleware', []));

        $container->add('router', $router);
        $container->add('dispatcher', $dispatcher);

        include $container['app']->getAppPath() . '/config/routes.php';
    }
}