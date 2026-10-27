<?php

declare(strict_types=1);

namespace FastD\Terminal;

use Symfony\Component\Console\Command\Command;

/**
 * 运行时命令注册表：ServiceProvider 可在这里注册命令。
 */
final class CommandRegistry
{
    /** @var list<Command|class-string<Command>> */
    private array $commands = [];

    public function add(Command|string $command): void
    {
        $this->commands[] = $command;
    }

    /**
     * @return list<Command|class-string<Command>>
     */
    public function all(): array
    {
        return $this->commands;
    }
}
