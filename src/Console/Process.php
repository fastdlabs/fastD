<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Console;

use FastD\Runtime;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

/**
 * Class Processor.
 */
class Process extends Runtime
{
    protected ConsoleOutput $output;

    public function handleException(Throwable $throwable): void
    {
        $this->handleOutput($throwable->getCode());
        $this->handleOutput($throwable->getFile());
        $this->handleOutput($throwable->getLine());
        $this->handleOutput($throwable->getMessage());
        $this->handleOutput($throwable->getTraceAsString());
    }

    public function handleInput()
    {
        return new ArgvInput(null, new InputDefinition([
            new InputArgument('name', InputArgument::OPTIONAL, 'The server action', 'status'),
            new InputArgument('worker', InputArgument::OPTIONAL, 'Worker number'),
            new InputOption('daemon', 'd', InputOption::VALUE_NONE, 'Do not ask any interactive question'),
        ]));
    }

    public function handleOutput($output)
    {
        $this->output->writeln(sprintf("<info>[%s]</info>: %s", date('Y-m-d H:i:s'), $output));
    }

    public function run(): void
    {
        try {
            $input = $this->handleInput();
            $name = $input->getArgument('name');
            if (empty($name)) {
                $this->output->writeln(sprintf('Process name is empty'));
                return ;
            }

            ['process' => $config] = app()->getBoostrap()['process'];
            $config = include $config;

            if (!isset($config[$name])) {
                throw new \RuntimeException(sprintf("Process %s not found", $name));
            }
            $worker = $input->getArgument('worker');
            $process = $config[$name];

            $process = new $process['process'];
            if ($worker > 1) {
                $process->fork($worker);
            } else {
                $process->start();
            }
        } catch (Throwable $throwable) {
            $this->handleException($throwable);
        }
    }
}
