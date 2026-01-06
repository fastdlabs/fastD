<?php

declare(strict_types=1);

namespace FastD\Server;

use FastD\Runtime;
use FastD\Application;
use FastD\Swoole\Server\HTTP;
use FastD\Swoole\Server\Swoole;
use FastD\Server\Events\OnResponsed;
use FastD\Server\Events\OnWorkerStarted;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Throwable;

class SwServer extends Runtime
{
    protected Swoole $server;

    public function __construct(string $environment, Application $application)
    {
        parent::__construct($environment, $application);

        ['url' => $url, 'setting' => $settings] = include $application->getPath() . '/config/swoole.php';

        // 配置默认路径
        $settings['pid_file'] = $application->getPath() . '/runtime/pid/' . $application->getName() . '.pid';
        $settings['log_rotation'] = SWOOLE_LOG_ROTATION_DAILY;

        $this->server = new class($url) extends HTTP { use OnResponsed, OnWorkerStarted; };

        $this->server->configure($settings);
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

        $action = $input->getArgument('action');
        return [$action, match ($action) {
            'start' => $this->server->start(),
            'stop' => $this->server->stop(),
            'reload' => $this->server->reload(),
            default => $this->server->status(),
        }];
    }

    public function onOutput(mixed $output): void
    {
        if (in_array($output[0], ['start', 'stop', 'reload'])) {
            return ;
        }
        // 获取服务器信息
        $url = $this->server->url;
        $setting = $this->server->config;

        echo "┌─────────────────────────────────────────────────────────┐" . PHP_EOL;
        echo "│                    FastD Swoole Server                  │" . PHP_EOL;
        echo "└─────────────────────────────────────────────────────────┘" . PHP_EOL;
        echo "Server Information:" . PHP_EOL;
        echo "  - Url: {$this->server->url}" . PHP_EOL;
        echo "  - Address: {$this->server->host}" . PHP_EOL;
        echo "  - Port: {$this->server->port}" . PHP_EOL;
        echo "  - Mode: {$this->server->mode}" . PHP_EOL;
        echo "  - SockType: {$this->server->sockType}" . PHP_EOL;
        echo "  - PID File: {$setting['pid_file']}" . PHP_EOL;
        echo "Configuration Options:" . PHP_EOL;

        // 显示配置项
        foreach ($setting as $key => $value) {
            if ($key != 'pid_file') {
                $valueStr = is_array($value) ? json_encode($value) : (is_bool($value) ? ($value ? 'true' : 'false') : $value);
                echo " - {$key}: {$valueStr}" . PHP_EOL;
            }
        }
    }

    public function onError(Throwable $throwable): void
    {
        echo "Error: " . $throwable->getMessage() . PHP_EOL;
        echo "Line: " . $throwable->getLine() . PHP_EOL;
        echo "File: " . $throwable->getFile() . PHP_EOL;
        echo "Trace: " . PHP_EOL . $throwable->getTraceAsString() . PHP_EOL;
    }
}