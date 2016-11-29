<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Provider;

use FastD\Config\Config;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;

class ConfigurableServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $config = new Config();

        $container->add('config', $config);
    }
}