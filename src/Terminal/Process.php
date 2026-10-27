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

        foreach ($workers['listener'] as $listener) {
            // 进程监听器从容器解析，支持 ServiceProvider 注册与依赖注入。
            $process->addListener($this->application->make($listener));
        }

        foreach ($workers['worker'] as $name => $worker) {
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