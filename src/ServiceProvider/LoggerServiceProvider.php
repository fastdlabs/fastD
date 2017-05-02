<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\ServiceProvider;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Monolog\Handler\StreamHandler;

/**
 * Class LoggerServiceProvider.
 */
class LoggerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $handlers = config()->get('log', []);

        empty($handlers) && \logger()->pushHandler(new StreamHandler('php://temp'));

        foreach ($handlers as $handler) {
            logger()->pushHandler(new $handler());
        }
    }
}
