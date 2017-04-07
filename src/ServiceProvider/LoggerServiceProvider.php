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
        $config = config();
        $logger = new Logger(app()->getName());

        $logs = $config->get('log');
        $path = app()->getPath().'/runtime/logs';

        foreach ($logs as $logHandle) {
            if (is_string($logHandle)) {
                $logger->pushHandler(new $logHandle($path.'/error.log', Logger::WARNING));
            } elseif ($logHandle instanceof HandlerInterface) {
                $logger->pushHandler($logHandle);
            }
        }

        $container->add('logger', $logger);
    }
}
