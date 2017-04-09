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
use Monolog\Handler\HandlerInterface;
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

        $logs = config()->get('log');
        $path = app()->getPath().'/runtime/logs';

        foreach ($logs as $logHandle) {
            list($handle, $name, $level) = array_pad($logHandle, 3, Logger::NOTICE);
            if (is_string($handle)) {
                $logger->pushHandler(new $handle($path.'/'.$name, $level));
            } elseif ($handle instanceof HandlerInterface) {
                $logger->pushHandler($handle);
            }
        }

        $container->add('logger', $logger);
    }
}
