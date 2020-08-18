<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Runtime\Swoole;


use FastD\Application;
use Monolog\Logger;
use Throwable;
use FastD\Runtime\Runtime;
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
class HTTP extends Runtime
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

    public function handleException(Throwable $throwable): void
    {
        $output = json([
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
            'trace' => explode("\r\n", $throwable->getTraceAsString()),
        ]);

        $this->handleLog(Logger::ERROR, $throwable->getMessage(), [
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
            'trace' => explode("\r\n", $throwable->getTraceAsString()),
        ]);

        $this->handleOutput($output);
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

    public function run()
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
