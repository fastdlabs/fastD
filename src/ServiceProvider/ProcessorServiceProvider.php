<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\ServiceProvider;


use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;

/**
 * Class ProcessorServiceProvider
 * @package FastD\ServiceProvider
 */
class ProcessorServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $path = app()->getPath() . '/config/process.php';
        if (file_exists($path)) {
            config()->set('processes', include $path);
        }
        return 0;
    }
}