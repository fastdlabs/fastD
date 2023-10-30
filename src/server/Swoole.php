<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Server;


use FastD\Runtime;
use FastD\Swoole\Server\AbstractServer;
use FastD\Swoole\Server\HTTP;
use Monolog\Logger;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

/**
 * Class App.
 */
class Swoole extends Runtime
{
    protected AbstractServer $server;
    protected ConsoleOutput $output;

    public function __construct($path)
    {
        $this->output = new ConsoleOutput();
        parent::__construct('swoole', $path);
        $config = load($this->path . '/src/config/server.php');
        // 配置默认路径
        $config['options']['pid_file'] = $config['options']['p_id'] ?? $this->path . '/runtime/pid/' . config()->get('name') . '.pid';
        $config['options']['log_rotation'] = $config['options']['log_rotation'] ?? SWOOLE_LOG_ROTATION_DAILY;
        config()->merge(['server' => $config]);

        $server = config()->get('server.server', HTTP::class);
        $this->server = new $server(config()->get('server.url'));
        $this->server->configure(config()->get('server.options'));
    }

    public function handleException(Throwable $throwable)
    {
        $data = [
            'msg' => $throwable->getMessage(),
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
            'trace' => explode(PHP_EOL, $throwable->getTraceAsString()),
        ];
        $this->handleLogger($throwable->getMessage(), $data);
        $this->output->writeln(sprintf('<info>[%s]</info>: %s', date('Y-m-d H:i:s'), $throwable->getMessage()));
        $this->output->writeln(sprintf('into file: %s(%s)', $throwable->getFile(), $throwable->getLine()));
        $this->output->writeln($throwable->getTraceAsString());
        return $data;
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

    }

    public function run(): void
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
