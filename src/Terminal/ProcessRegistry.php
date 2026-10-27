<?php

declare(strict_types=1);

namespace FastD\Terminal;

use FastD\Event\EventListenerInterface;
use FastD\Swoole\Process\Worker;

/**
 * 运行时进程注册表：ServiceProvider 可注册 Worker 与进程事件监听器。
 */
final class ProcessRegistry
{
    /** @var array<string, Worker|class-string<Worker>> */
    private array $workers = [];

    /** @var list<EventListenerInterface|class-string<EventListenerInterface>> */
    private array $listeners = [];

    public function addWorker(Worker|string $worker, ?string $name = null): void
    {
        if (is_string($worker)) {
            $this->workers[$name ?? $worker] = $worker;
            return;
        }

        $this->workers[$name ?? $worker->name] = $worker;
    }

    public function addListener(EventListenerInterface|string $listener): void
    {
        $this->listeners[] = $listener;
    }

    /**
     * @return array<string, Worker|class-string<Worker>>
     */
    public function workers(): array
    {
        return $this->workers;
    }

    /**
     * @return list<EventListenerInterface|class-string<EventListenerInterface>>
     */
    public function listeners(): array
    {
        return $this->listeners;
    }
}
