<?php

declare(strict_types=1);

namespace FastD\Terminal;

use FastD\Runtime;
use FastD\Terminal;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

class Console extends Runtime
{
    public function onInput(): mixed
    {
        $app = new Application(container()->getName());

        $commands = container()->need('commands');

        foreach ($commands as $command) {
            $app->addCommand(new $command);
        }

        $app->run(new ArgvInput(), new ConsoleOutput());
    }

    public function onOutput($output): void
    {
        $message = sprintf("[%s] %s", date('Y-m-d H:i:s'), $output);
        echo $message . PHP_EOL; // 正常输出
    }

    public function onError(Throwable $throwable): void
    {
        $message = sprintf("[%s] ERROR: %s", date('Y-m-d H:i:s'), $throwable->getMessage());
        echo "\033[31m" . $message . "\033[0m" . PHP_EOL; // 红色输出
    }
}
