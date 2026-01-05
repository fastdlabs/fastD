<?php

declare(strict_types=1);

namespace FastD\Environment;

use FastD\Application;
use FastD\Http\Request\ServerRequest;
use FastD\Environment;
use FastD\Http\Request\SwooleServerRequest;
use FastD\Http\Response\Response;
use FastD\Swoole\Server\HTTP;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

class Swoole extends Environment
{
    protected \FastD\Swoole\Server\Swoole $server;

    public function __construct(Application $application)
    {
        parent::__construct('swoole', $application);

        ['url' => $url, 'setting' => $setting] = include $application->getPath() . '/config/swoole.php';

        // 配置默认路径
        $setting['pid_file'] = $application->getPath() . '/runtime/pid/' . $application->getName() . '.pid';
        $setting['log_rotation'] = SWOOLE_LOG_ROTATION_DAILY;

        $this->server = new class extends HTTP {
            public function onResponse(ServerRequest $serverRequest): Response
            {
                return app()->dispatch($serverRequest);
            }
        };

        $this->server->configure($setting);
    }

    public function onInput(): mixed
    {
        $input = new ArgvInput(null, new InputDefinition([
            new InputArgument('action', InputArgument::OPTIONAL, 'The server action', 'status'),
            new InputOption('daemon', 'd', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
        ]));

        if ($input->hasParameterOption(['--daemon', '-d'], true)) {
            $this->server->daemon();
        }

        return match ($input->getArgument('action')) {
            'start' => $this->server->start(),
            'stop' => $this->server->stop(),
            'reload' => $this->server->reload(),
            default => $this->server->status(),
        };
    }

    public function onOutput(mixed $output): void
    {
        echo sprintf("[%s] %s", date('Y-m-d H:i:s'), $output) . PHP_EOL;
    }

    public function onError(Throwable $throwable): void
    {
        $data = [
            'msg' => $throwable->getMessage(),
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
            'trace' => explode(PHP_EOL, $throwable->getTraceAsString()),
        ];
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
