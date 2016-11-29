<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Provider;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Routing\RouteCollection;

class RouteServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->add('router', new RouteCollection());

        include $container['app']->getAppPath() . '/config/routes.php';
    }
}