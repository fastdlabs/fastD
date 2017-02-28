<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;


use FastD\ServiceProvider\SwooleServiceProvider;
use FastD\Servitization\Server\HTTPServer;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
        $application->register(new SwooleServiceProvider());

        $server = config()->get('swoole.class', HTTPServer::class);

        $this->server = $server::createServer(
            $application->getName(),
            config()->get('swoole.listen'),
            config()->get('swoole.options', [])
        );

        $this->initListeners();
        $this->initProcesses();
        $this->initConnectionPool();
    }

    /**
     * @return swoole_server
     */
    public function getSwoole()
    {
        return $this->server->getSwoole();
    }

    /**
     * 初始化连接池
     *
     * @return $this
     */
    public function initConnectionPool()
    {


        return $this;
    }

    /**
     * @return $this
     */
    public function initListeners()
    {
        $listeners = config()->get('listeners', []);
        foreach ($listeners as $listener) {
            $this->server->listen(new $listener['class'](
                app()->getName() . ' ports',
                $listener['listen'],
                isset($listener['options']) ? $listener['options'] : []
            ));
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function initProcesses()
    {
        $processes = config()->get('processes', []);
        foreach ($processes as $process) {
            $this->server->process(new $process);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function daemon()
    {
        $this->server->daemon();

        return $this;
    }

    /**
     * @return int
     */
    public function start()
    {
        return $this->server->start();
    }

    /**
     * @return int
     */
    public function stop()
    {
        return $this->server->shutdown();
    }

    /**
     * @return int
     */
    public function restart()
    {
        return $this->server->restart();
    }

    /**
     * @return int
     */
    public function reload()
    {
        return $this->server->reload();
    }

    /**
     * @return int
     */
    public function status()
    {
        return $this->server->status();
    }

    /**
     * @param array $dir
     * @return int
     */
    public function watch(array $dir = ['.'])
    {
        return $this->server->watch($dir);
    }

    /**
     * @param InputInterface $input
     */
    public function run(InputInterface $input)
    {
        if ($input->hasParameterOption(['--daemon', '-d'], true)) {
            $this->daemon();
        }

        switch ($input->getArgument('action')) {
            case 'start':
                if ($input->hasParameterOption(['--dir'])) {
                    $this->watch([$input->getOption('dir')]);
                } else {
                    $this->start();
                }
                break;
            case 'stop':
                $this->stop();
                break;
            case 'reload':
                $this->reload();
                break;
            case 'status':
            default:
                $this->status();
        }
    }
}