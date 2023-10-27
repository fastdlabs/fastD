<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\runtime\Console;


use fastd\server\runtime;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

/**
 * Class AppConsole.
 */
class Console extends runtime
{
    public function __construct(\FastD\Application $application)
    {
        parent::__construct('console', $application);
    }

    public function handleException(Throwable $throwable):void
    {
        throw $throwable;
    }

    /**
     * @return ArgvInput
     */
    public function handleInput()
    {
        return new ArgvInput();
    }

    public function handleOutput($output)
    {
        return $output;
    }

    protected function scanCommands()
    {
        $path = app()->getPath().'/src/config/commands.php';

        return include $path;
    }

    public function run(): void
    {
        $app = new Application('FastD', \FastD\Application::VERSION);

        $input = $this->handleInput();

        $output = new ConsoleOutput();

        foreach ($this->scanCommands() as $command) {
            $app->add(new $command());
        }

        try {
            $app->run($input, $output);
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }
}