<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Console;


use FastD\Console\Command\RouteList;
use FastD\Runtime;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

/**
 * Class AppConsole.
 */
class Console extends Runtime
{

    protected ConsoleOutput $output;

    public function __construct(\FastD\Application $application)
    {
        parent::__construct($application);
        $this->output = new ConsoleOutput();
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
        return new ArgvInput();
    }

    public function handleOutput($output): void
    {
        $this->output->writeln(sprintf("<info>[%s]</info>: %s", date('Y-m-d H:i:s'), $output));
    }

    public function run(): void
    {
        $app = new Application(app()->getName(), \FastD\Application::VERSION);

        $commands = app()->getBootstrap('commands');

        $commands = array_merge([
            RouteList::class,
        ], $commands);

        foreach ($commands as $command) {
            $app->add(new $command());
        }

        $app->run($this->handleInput(), $this->output);
    }
}
