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

class SwooleServiceProvider implements ServiceProviderInterface
{
    const SERVER_NAME = 'swoole';

    public function register(Container $container)
    {
        // TODO: Implement register() method.
    }

    public function getName()
    {
        return static::SERVER_NAME;
    }
}