<?php

declare(strict_types=1);

namespace FastD\Server;

use FastD\Application;
use FastD\Http\Request\ServerRequest;
use FastD\Runtime;
use FastD\Server\Events\OnResponsed;
use FastD\Server\Events\OnWorkerStarted;
use FastD\Swoole\Server\HTTP;
use FastD\Terminal;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Throwable;

class SwServer extends Runtime
{
    protected array $config;

    protected \FastD\Swoole\Server\Swoole $server;

    public function __construct(string $environment, Application $application)
    {
        parent::__construct($environment, $application);

        $this->config = include $application->getPath() . '/config/swoole.php';

        // 配置默认路径
        $this->config['setting']['pid_file'] = $application->getPath() . '/runtime/pid/' . $application->getName() . '.pid';
        $this->config['setting']['log_rotation'] = SWOOLE_LOG_ROTATION_DAILY;

        $this->server = new class extends HTTP { use OnResponsed, OnWorkerStarted; };

        $this->server->configure($this->config['setting']);
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
        $url = $this->config['url'];
        $setting = $this->config['setting'];

        // 解析URL以获取主机和端口
        $parsedUrl = parse_url($url);
        $host = $parsedUrl['host'];
        $port = $parsedUrl['port'];

        echo "┌─────────────────────────────────────────────────────────┐" . PHP_EOL;
        echo "│                    FastD Swoole Server                  │" . PHP_EOL;
        echo "└─────────────────────────────────────────────────────────┘" . PHP_EOL;
        echo "Server Information:" . PHP_EOL;
        echo "  - Address: {$host}" . PHP_EOL;
        echo "  - Port: {$port}" . PHP_EOL;
        echo "  - PID File: {$setting['pid_file']}" . PHP_EOL;
        echo "  - Log Rotation: {$setting['log_rotation']}" . PHP_EOL;
        echo "Configuration Options:" . PHP_EOL;

        // 显示配置项
        foreach ($setting as $key => $value) {
            if (!in_array($key, ['pid_file', 'log_rotation'])) {
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