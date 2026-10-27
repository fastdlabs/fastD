<?php

declare(strict_types=1);

namespace FastD\Server;

use FastD\Runtime;
use FastD\Swoole\Server;
use FastD\Swoole\SwooleEventDispatcher;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Throwable;

class SwServer extends Runtime
{
    protected ?Server $server = null;

    public function bootstrap(): void
    {
        parent::bootstrap();

        $swooleConfig = $this->application->config('swoole');
        
        $servers = $swooleConfig['servers'] ?? [];
        $listeners = $swooleConfig['listeners'] ?? [];
        $options = $swooleConfig['options'] ?? [];

        // 配置默认路径
        $options['pid_file'] = $this->application->getRootPath() . '/runtime/pid/' . $this->application->getName() . '.pid';
        $options['log_file'] = $this->application->getRootPath() . '/runtime/logs/' . date('Ym') . '/error.log';
        $options['log_rotation'] = SWOOLE_LOG_ROTATION_DAILY;

        $listenerProvider = $this->application->get('event')->listenerProvider;
        $this->server = new Server($options, new SwooleEventDispatcher($listenerProvider));

        foreach ($listeners as $listener) {
            $listenerProvider->addListener(new $listener);
        }

        foreach ($servers as $name => $server) {
            $listenerClass = $server['listener']
                ?? throw new \RuntimeException(sprintf('Swoole server "%s" requires a listener.', $name));

            $this->server->listen($server['host'], $server['port'], new $listenerClass());
        }
        // 替换原有事件调度
        $this->application->add('event', $this->server->eventDispatcher);
    }

    public function input(): mixed
    {
        $input = new ArgvInput(null, new InputDefinition([
            new InputArgument('action', InputArgument::OPTIONAL, 'The server action', 'status'),
            new InputOption('daemon', 'd', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
        ]));

        if ($input->hasParameterOption(['--daemon', '-d'], true)) {
            $this->server->daemon();
        }

        $action = $input->getArgument('action');
        return match ($action) {
            'start' => $this->server->start(),
            'stop' => $this->server->stop(),
            'reload' => $this->server->reload(),
            default => $this->server->status(),
        };
    }

    public function output(mixed $output): void
    {
        // 由 swoole event 监听输出
        $this->application->get('logger')->info('SwServer output', [
            'output' => $output,
        ]);
    }

    public function abort(Throwable $throwable): void
    {
        $data = [
            'msg' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
            'trace' => explode(PHP_EOL, $throwable->getTraceAsString()),
        ];
        $this->application->get('logger')->error($throwable->getMessage(), $data);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}