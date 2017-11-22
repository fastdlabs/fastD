<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
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
     *
     * @return mixed
     */
    public function register(Container $container)
    {
        $path = app()->getPath().'/config/process.php';
        if (file_exists($path)) {
            config()->set('processes', include $path);
        }

        return 0;
    }
}
