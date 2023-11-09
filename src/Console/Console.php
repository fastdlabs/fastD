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
    public function handleException(Throwable $throwable): void
    {
        throw $throwable;
    }

    public function handleInput()
    {
        return new ArgvInput();
    }

    public function handleOutput($output)
    {
        return $output;
    }

    public function run(): void
    {
        $app = new Application(app()->getName(), \FastD\Application::VERSION);

        ['commands' => $commands] = app()->getBoostrap();

        $commands = include $commands;

        foreach ($commands as $command) {
            $app->add(new $command());
        }

        $input = $this->handleInput();

        $output = new ConsoleOutput();

        try {
            $app->run($input, $output);
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }
}
