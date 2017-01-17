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

class SwooleServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->get('config')->load(app()->getAppPath() . '/config/server.php');
    }
}