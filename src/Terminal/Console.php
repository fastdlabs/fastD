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
        $app = new Application(container()->getName(), \FastD\Application::VERSION);

        $commands = container()->config('command');

        foreach ($commands as $command) {
            $app->addCommand(new $command);
        }

        $app->run(new ArgvInput(), new ConsoleOutput());
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
