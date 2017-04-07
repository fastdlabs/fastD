<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\ServiceProvider;

use FastD\Config\Config;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;

/**
 * Class ConfigServiceProvider.
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $dir = app()->getPath().'/config';
        $container->get('config')->load($dir.'/config.php');
        $container->get('config')->merge([
            'database' => load($dir.'/database.php'),
            'cache' => load($dir.'/cache.php'),
        ]);
    }
}
