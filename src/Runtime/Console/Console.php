<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Runtime\Console;


use FastD\Runtime\Runtime;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

/**
 * Class AppConsole.
 */
class Console extends Runtime
{
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

    public function run()
    {
        $app = new Application('FastD', \FastD\Application::VERSION);

        $input = $this->handleInput();

        $output = $this->handleOutput(new ConsoleOutput());

        try {
            $app->run($input, $output);
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }
}
