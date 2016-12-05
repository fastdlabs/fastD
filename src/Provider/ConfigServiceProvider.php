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

/**
 * Class ConfigServiceProvider
 * @package FastD\Provider
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container)
    {
        $config = new Config();

        $container->add('config', $config);
    }
}