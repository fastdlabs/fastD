<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;

use FastD\ServiceProvider\SwooleProvider;
use FastD\Servitization\Server\HTTPServer;
use swoole_http_response;
use swoole_server;

/**
 * Class App
 *
 * @package FastD
 */
class Server
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @var \FastD\Swoole\Server
     */
    protected $server;

    /**
     * Server constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;

        $application->register(new SwooleProvider());

        $server = config()->get('swoole.class', HTTPServer::class);

        $this->server = $server::createServer($application->getName(), config()->get('swoole.listen'), config()->get('swoole.options'));

        $this->initListeners();
        $this->initProcesses();
        $this->initConnectionPool();

        $this->server->bootstrap();
    }

    public function getSwoole()
    {
        return $this->server->getSwoole();
    }

    /**
     *
     */
    public function initConnectionPool()
    {

    }

    /**
     * @return $this
     */
    public function initListeners()
    {
        $ports = $this->application->get('config')->get('ports', []);
        foreach ($ports as $port) {
            $class = $port['class'];
            $this->listen(new $class('ports', $port['listen'], isset($port['options']) ? $port['options'] : []));
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function initProcesses()
    {
        $processes = $this->application->get('config')->get('processes', []);
        foreach ($processes as $process) {
            $this->process(new $process);
        }
        return $this;
    }

    public function daemon()
    {
        return $this->server->daemon();
    }

    public function start()
    {
        return $this->server->start();
    }

    public function stop()
    {
        return $this->server->shutdown();
    }

    public function restart()
    {
        return $this->server->restart();
    }

    public function reload()
    {
        return $this->server->reload();
    }

    public function status()
    {
        return $this->server->status();
    }

    public function watch(array $dir = ['.'])
    {
        return $this->server->watch($dir);
    }
}