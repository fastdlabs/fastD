<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2017
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD\Process;

use FastD\Runtime;
use Throwable;

/**
 * Class Processor.
 */
class Process extends Runtime
{
    public function handleLog(int $level, string $message, array $context = [])
    {
        // TODO: Implement handleLog() method.
    }

    public function handleException(Throwable $throwable)
    {
        // TODO: Implement handleException() method.
    }

    public function handleInput()
    {
        // TODO: Implement handleInput() method.
    }

    public function handleOutput($output)
    {
        // TODO: Implement handleOutput() method.
    }

    public function start()
    {
        $input = $this->handleInput();
    }
}
