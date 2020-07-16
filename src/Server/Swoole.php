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
use FastD\Swoole\Handlers\HTTPHandler;
use FastD\Swoole\HTTP;
use FastD\Swoole\Server\ServerAbstract;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class App.
 */
class Swoole extends Application
{
    protected ServerAbstract $server;

    public function bootstrap(Container $container): void
    {
        parent::bootstrap($container);

        $this->server = config()->get('server.adapter', HTTP::class);
    }

    public function daemon()
    {
        $this->server->daemon();

        return $this;
    }

    public function handleInput(): Stream
    {
        $this->server->handler(HTTPHandler::class);
    }

    public function handleOutput(Stream $stream): void
    {

    }
}
