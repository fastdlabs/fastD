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
    public function handleException(Throwable $throwable): void {}

    public function handleInput()
    {
        return new ArgvInput();
    }

    public function handleOutput($output): void {}

    public function run(): void
    {
        $app = new Application(app()->getName(), \FastD\Application::VERSION);

        $commands = app()->getBoostrap('commands');

        $commands = array_merge([
            RouteList::class,
        ], $commands);

        foreach ($commands as $command) {
            $app->add(new $command());
        }

        $app->run($this->handleInput(), new ConsoleOutput());
    }
}
