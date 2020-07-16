<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2019
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Providers;


use Exception;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use RuntimeException;

class ExceptionProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $config = config()->get('exception');

        $exception = new $config['adapter']($config['options']);

        $container->add('exception', $exception);

        /*set_exception_handler([app(), 'handleException']);

        set_error_handler(function ($code, $message) {
            throw new RuntimeException($message, $code);
        }, $config['options']['level'] ?? E_ERROR);*/
    }
}
