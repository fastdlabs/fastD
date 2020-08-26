<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Runtime\Process;

use FastD\Application;
use FastD\Runtime\Runtime;
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

    public function __construct(Application $application)
    {
        parent::__construct($application);

        $this->output = new ConsoleOutput();

        $config = load(app()->getPath() . '/config/process.php');

        config()->merge(['process' => $config]);
    }

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

    public function handleOutput($meesage)
    {
        $this->output->writeln(sprintf("<info>[%s]</info>: %s", date('Y-m-d H:i:s'), $meesage));
    }

    public function run()
    {
        try {
            $input = $this->handleInput();

            $name = $input->getArgument('name');
            $worker = $input->getArgument('worker', 1);

            $process = config()->get('process.'.$name);

            if (empty($process)) {
                throw new \RuntimeException(sprintf("Process %s not found", $name));
            }

            $obj = new $process['process']('fastd');
            if ($worker > 1) {
                $obj->fork($worker);
            } else {
                $obj->start();
            }
        } catch (Throwable $throwable) {
            $this->handleException($throwable);
        }
    }
}
