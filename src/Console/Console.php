<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Console;


use FastD\Runtime;
use Symfony\Component\Console\Input\ArgvInput;
use Throwable;

/**
 * Class AppConsole.
 */
class Console extends Runtime
{
    public function start()
    {
        // TODO: Implement start() method.
    }

    public function handleLog(int $level, string $message, array $context = [])
    {
        // TODO: Implement handleLog() method.
    }

    public function handleException(Throwable $throwable)
    {
        // TODO: Implement handleException() method.
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
        // TODO: Implement handleOutput() method.
    }
}
