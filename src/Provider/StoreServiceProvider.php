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
use FastD\Store\Store;

class StoreServiceProvider implements ServiceProviderInterface
{
    const SERVICE_NAME = 'store';

    public function register(Container $container)
    {
        $container->add('store', function (Container $container) {
            return new Store($container);
        });
    }

    public function getName()
    {
        return static::SERVICE_NAME;
    }
}