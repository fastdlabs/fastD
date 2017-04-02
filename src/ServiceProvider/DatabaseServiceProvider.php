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
use FastD\Pool\DatabasePool;

/**
 * Class DatabaseServiceProvider
 * @package FastD\ServiceProvider
 */
class DatabaseServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container)
    {
        $config = config()->get('database', []);

        $container->add('database', new DatabasePool($config));

        unset($config);
    }
}