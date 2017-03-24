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
        $logger = new Logger($config->get('name'));

        $log = $config->get('log');
        $path = app()->getPath() . '/runtime/logs';

        if (!isset($log['error'])) {
            $logger->pushHandler(new StreamHandler($path . '/error.log', Logger::WARNING));
        } else if (is_string($log['error'])) {
            $logger->pushHandler(new $log['error']($path . '/error.log', Logger::WARNING));
        } else if ($log['error'] instanceof HandlerInterface) {
            $logger->pushHandler($log['error']);
        }

        $container->add('logger', $logger);
    }
}