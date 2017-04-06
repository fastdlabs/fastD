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
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class LoggerServiceProvider
 * @package FastD\ServiceProvider
 */
class LoggerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container)
    {
        $config = config();
        $logger = new Logger(app()->getName());

        $logs = $config->get('log');
        $path = app()->getPath() . '/runtime/logs';

        foreach ($logs as $logHandle) {
            if (is_string($logHandle)) {
                $logger->pushHandler(new $logHandle($path . '/error.log', Logger::WARNING));
            } else if ($logHandle instanceof HandlerInterface) {
                $logger->pushHandler($logHandle);
            }
        }

        $container->add('logger', $logger);
    }
}