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

        [
            'listen' => $listens,
            'worker' => $worker,
            'setting' => $settings
        ] = $this->application->config('swoole');

        // 配置默认路径
        $settings['pid_file'] = $this->application->getRootPath() . '/runtime/pid/' . $this->application->getName() . '.pid';
        $settings['log_file'] = $this->application->getRootPath() . '/runtime/logs/' . date('Ym') . '/error.log';
        $settings['log_rotation'] = SWOOLE_LOG_ROTATION_DAILY;

        $listenerProvider = $this->application->get('event')->listenerProvider;
        $this->server = new Server($settings, new SwooleEventDispatcher($listenerProvider));

        foreach ($worker as $item) {
            $listenerProvider->addListener(new $item);
        }

        foreach ($listens as $listen) {
            $this->server->listen($listen['host'], $listen['port'], new $listen['worker']);
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
        info('SwServer output', [
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
        error($throwable->getMessage(), $data);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}