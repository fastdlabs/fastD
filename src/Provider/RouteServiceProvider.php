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
    const SERVICE_NAME = 'routing';

    public function register(Container $container)
    {
        $container->add($this->getName(), new RouteCollection());

        include $container->getAppPath() . '/config/routes.php';
    }

    public function getName()
    {
        return static::SERVICE_NAME;
    }
}