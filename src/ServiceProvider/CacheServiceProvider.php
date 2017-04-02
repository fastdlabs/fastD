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
use FastD\Pool\CachePool;


/**
 * Class CacheServiceProvider
 * @package FastD\ServiceProvider
 */
class CacheServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $config = $container->get('config')->get('cache');

        $container->add('cache', new CachePool($config));

        unset($config);
    }
}