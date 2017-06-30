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
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractHandler;

/**
 * Class LoggerServiceProvider
 *
 * @package ServiceProvider
 */
class LoggerServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        $handlerDefines = config()->get('log', []);

        foreach ($handlerDefines as $handlerDefine) {
            $handler = array_shift($handlerDefine);
            $formatter = array_shift($handlerDefine);

            if (is_string($handler)) {
                $reflection = new \ReflectionClass($handler);
                $parameters = $reflection->getConstructor()->getParameters();
                $args = array_pad($handlerDefine, count($parameters), null);
                $handler = $reflection->newInstanceArgs($args);
            }

            if ($handler instanceof AbstractHandler) {
                if (is_null($formatter)) {
                    $formatter = new LineFormatter();
                }
                $handler->setFormatter(is_string($formatter) ? new $formatter() : $formatter);
                logger()->pushHandler($handler);
            }
        }
    }
}