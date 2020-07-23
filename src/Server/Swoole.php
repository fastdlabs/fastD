<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Server;


use FastD\Application;
use FastD\Container\Container;
use FastD\Http\Stream;
use FastD\Http\SwooleServerRequest;
use FastD\Runtime;
use FastD\Swoole\Handlers\HTTPHandler;
use FastD\Swoole\HTTP;
use FastD\Swoole\Server\HTTPServer;
use FastD\Swoole\Server\ServerAbstract;
use Symfony\Component\Console\Input\InputInterface;
use Throwable;

/**
 * Class App.
 */
class Swoole extends Runtime
{
    public function handleLog(int $level, string $message, array $context = [])
    {
        // TODO: Implement handleLog() method.
    }

    public function handleException(Throwable $throwable)
    {
        // TODO: Implement handleException() method.
    }

    public function handleInput()
    {
        return SwooleServerRequest::createServerRequestFromSwoole($swooleRequet);
    }

    public function handleOutput($output)
    {
        // TODO: Implement handleOutput() method.
    }

    public function start()
    {
        $server = new HTTPServer();

        $server->start();
    }
}
