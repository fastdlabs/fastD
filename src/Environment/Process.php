<?php

declare(strict_types=1);

namespace FastD\Environment;

use FastD\Application;
use FastD\Environment;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

class Process extends Environment
{
    public function onInput(): string
    {
        $input = new ArgvInput(null, new InputDefinition([
            new InputArgument('name', InputArgument::OPTIONAL, 'The server action'),
            new InputArgument('worker', InputArgument::OPTIONAL, 'Worker number'),
            new InputOption('daemon', 'd', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
        ]));

        $name = $input->getArgument('name');
        if (empty($name)) {
            return 'Process name is empty';
        }

        $config = include static::$application->getPath() . '/config/process.php';

        if (!isset($config[$name])) {
            return sprintf('Process "%s" not found', $name);
        }

        $worker = $input->getArgument('worker');
        $process = $config[$name];

        $process = new $process['process'];
        if ($worker > 1) {
            $process->fork($worker);
        } else {
            $process->start();
        }
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