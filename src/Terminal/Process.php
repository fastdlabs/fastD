<?php

declare(strict_types=1);

namespace FastD\Terminal;

use FastD\Runtime;
use Throwable;

class Process extends Runtime
{
    public function input(): string
    {
        $process = new \FastD\Swoole\Process();

        $workers = $this->application->config('process');
        $registry = $this->application->has('processes')
            ? $this->application->get('processes')
            : new ProcessRegistry();

        $listeners = array_merge($workers['listener'] ?? [], $registry->listeners());

        foreach ($listeners as $listener) {
            // 进程监听器从容器解析，支持 ServiceProvider 注册与依赖注入。
            $process->addListener(is_object($listener) ? $listener : $this->application->make($listener));
        }

        $workerMap = array_merge($workers['worker'] ?? [], $registry->workers());

        foreach ($workerMap as $name => $worker) {
            if (is_object($worker)) {
                $process->addWorker($worker);
                continue;
            }

            // Worker 支持容器中预注册实例；未注册时保持原有 name 构造行为。
            $process->addWorker(
                $this->application->has($worker)
                    ? $this->application->get($worker)
                    : new $worker($name)
            );
        }

        $process->start();

        return '';
    }

    public function output($output): void
    {
        $message = sprintf("[%s] %s", date('Y-m-d H:i:s'), $output);
        echo $message . PHP_EOL; // 正常输出
    }

    public function abort(Throwable $throwable): void
    {
        $message = sprintf("[%s] ERROR: %s", date('Y-m-d H:i:s'), $throwable->getMessage());
        echo "\033[31m" . $message . "\033[0m" . PHP_EOL; // 红色输出
    }
}