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
use Monolog\Handler\AbstractHandler;
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
        $path = app()->getPath().'/runtime/logs';

        empty($handlers) && logger()->pushHandler(new StreamHandler('php://temp'));

        foreach ($handlers as $handler) {
            list($handle, $name, $level, $format) = array_pad($handler, 4, null);
            if (is_string($handle)) {
                $handle = new $handle($path.'/'.$name, $level);
            }
            if ($handle instanceof AbstractHandler) {
                null !== $format && $handle->setFormatter(is_string($format) ? new $format() : $format);
                Logger()->pushHandler($handle);
            }
        }

    }
}
