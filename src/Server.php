<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD;

use FastD\ServiceProvider\SwooleServiceProvider;
use FastD\Servitization\Server\HTTPServer;
use swoole_server;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class App.
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
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $application->register(new SwooleServiceProvider());

        $server = config()->get('server.class', HTTPServer::class);

        $this->server = $server::createServer(
            $application->getName(),
            config()->get('server.host'),
            config()->get('server.options', [])
        );

        $this->initListeners();
        $this->initProcesses();
    }

    /**
     * @return swoole_server
     */
    public function getSwoole()
    {
        return $this->server->getSwoole();
    }

    /**
     * @return Swoole\Server
     */
    public function bootstrap()
    {
        return $this->server->bootstrap();
    }

    /**
     * @return $this
     */
    public function initListeners()
    {
        $listeners = config()->get('server.listeners', []);
        foreach ($listeners as $listener) {
            $this->server->listen(new $listener['class'](
                app()->getName().' ports',
                $listener['host'],
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
        $processes = config()->get('server.processes', []);
        foreach ($processes as $process) {
            $this->server->process(new $process(app()->getName().' process'));
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
        $server = $this->bootstrap();

        app()->add('server', $server->getSwoole());

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
     *
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
