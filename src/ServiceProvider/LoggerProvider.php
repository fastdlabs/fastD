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
class LoggerProvider implements ServiceProviderInterface
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
        $path = !isset($log['path']) ? null : $log['path'];
        if (empty($path) || '/' !== $path{0}) {
            $path = app()->getPath() . '/' . $path;
        }

        if (!isset($log['info'])) {
            $logger->pushHandler(new StreamHandler($path . '/info.log', Logger::INFO));
        } else if (is_string($log['info'])) {
            $logger->pushHandler(new $log['info']($path . '/info.log', Logger::INFO));
        } else if ($log['info'] instanceof HandlerInterface) {
            $logger->pushHandler($log['info']);
        }

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