<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Providers;

use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Formatter\NormalizerFormatter;
use Monolog\Handler\AbstractHandler;
use Monolog\Logger;

/**
 * Class LoggerServiceProvider.
 */
class LoggerProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container): void
    {
        $handlers = config()->get('logger');
        $logpath = app()->getPath().'/runtime/logs/'.date('Ymd');

        $logger = new Logger(app()->getName());

        foreach ($handlers as $name => $config) {
            $handler = new $config['handler']($logpath.'/'.$name, $config['level']);
            $formatter = $config['formatter'] ?? NormalizerFormatter::class;
            $handler->setFormatter(new $formatter());
            $logger->pushHandler($handler);
        }
    }
}
