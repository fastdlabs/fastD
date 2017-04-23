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
use Monolog\Logger;

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
        $logger = new Logger(app()->getName());

        $handlers = config()->get('log');
        $path = app()->getPath().'/runtime/logs';

        if (empty($handlers)) {
            $logger->pushHandler(new StreamHandler('php://temp'), Logger::DEBUG);
        }

        foreach ($handlers as $handler) {
            list($handle, $name, $level, $format) = array_pad($handler, 4, null);
            if (is_string($handle)) {
                $handle = new $handle($path.'/'.$name, $level);
            }
            null !== $format && $handle->setFormatter(is_string($format) ? new $format() : $format);
            $logger->pushHandler($handle);
        }

        $container->add('logger', $logger);
    }
}
