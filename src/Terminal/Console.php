<?php

declare(strict_types=1);

namespace FastD\Terminal;

use FastD\Runtime;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

class Console extends Runtime
{
    public function input(): mixed
    {
        $app = new Application($this->application->getName(), \FastD\Application::VERSION);

        $registry = $this->application->has('commands')
            ? $this->application->get('commands')
            : new CommandRegistry();

        $commands = array_merge($this->application->config('command'), $registry->all());

        foreach ($commands as $command) {
            // 命令统一从容器解析，支持 ServiceProvider 注册与构造器注入。
            $app->addCommand($command instanceof \Symfony\Component\Console\Command\Command
                ? $command
                : $this->application->make($command));
        }

        $app->run(new ArgvInput(), new ConsoleOutput());

        return null;
    }

    public function output($output): void
    {
        echo sprintf("[%s] %s", date('Y-m-d H:i:s'), $output) . PHP_EOL;
    }

    public function abort(Throwable $throwable): void
    {
        echo "\033[31m" . sprintf("[%s] ERROR: %s", date('Y-m-d H:i:s'), $throwable->getMessage()) . "\033[0m" . PHP_EOL;
    }
}
