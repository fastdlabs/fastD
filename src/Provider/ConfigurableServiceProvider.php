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
    const SERVICE_NAME = 'config';

    public function register(Container $container)
    {
        $config = new Config();

        $config->load($container->getAppPath() . '/config/app.php');

        $env = $config->get('env', 'dev');

        if ($env !== 'prod') {
            $container->enableDebug();
        }

        $container->add($this->getName(), $config);
    }

    public function getName()
    {
        return static::SERVICE_NAME;
    }
}