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

        $workers = container()->config('process');

        foreach ($workers as $name => $worker) {
            $process->addWorker(new $worker($name));
        }

        $process->start();
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