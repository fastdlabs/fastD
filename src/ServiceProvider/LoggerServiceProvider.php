<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace ServiceProvider;


use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LoggerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $logger = new Logger('');
        $logger->pushHandler(new StreamHandler(''), Logger::INFO);
        $container->add('logger', $logger);
    }
}