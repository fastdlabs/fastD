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
use Throwable;
use FastD\Runtime;
use FastD\Swoole\Server\HTTPServer;
use FastD\Swoole\Server\ServerAbstract;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class App.
 */
class Swoole extends Runtime
{
    protected ServerAbstract $server;
    protected ConsoleOutput $output;

    /**
     * Application constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);

        $config = load(app()->getPath() . '/config/server.php');

        config()->merge(['server' => $config]);

        $this->bootstrap();
    }

    public function bootstrap()
    {
        $this->output = new ConsoleOutput();

        $server = config()->get('server.server', HTTPServer::class);

        $this->server = new $server(config()->get('server.url'));

        $this->server->configure(config()->get('server.options'));
    }

    public function handleLog(int $level, string $message, array $context = [])
    {

    }

    public function handleException(Throwable $throwable)
    {
        echo $throwable->getMessage().PHP_EOL;
        echo $throwable->getLine().PHP_EOL;
        echo $throwable->getFile().PHP_EOL;
        echo $throwable->getTraceAsString();
    }

    /**
     * @return ArgvInput
     */
    public function handleInput()
    {
        return new ArgvInput(null, new InputDefinition([
            new InputArgument('action', InputArgument::OPTIONAL, 'The server action', 'status'),
            new InputOption('daemon', 'd', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
        ]));
    }

    /**
     * @param $output
     */
    public function handleOutput($output)
    {
        return;
    }

    public function start()
    {
        try {
            $input = $this->handleInput();
            if ($input->hasParameterOption(['--daemon', '-d'], true)) {
                $this->server->daemon();
            }
            switch ($input->getArgument('action')) {
                case 'start':
                    $handle = config()->get('server.handle');
                    $this->server->handle($handle);
                    $this->server->start();
                    break;
                case 'stop':
                    $this->server->stop();
                    break;
                case 'reload':
                    $this->server->reload();
                    break;
                case 'status':
                default:
                    $this->server->status();
            }
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }
}
