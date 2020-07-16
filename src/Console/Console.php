<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Console;

use FastD\Application;
use FastD\Http\Stream;
use Symfony\Component\Console\Application as SymfonyCli;
use Throwable;

/**
 * Class AppConsole.
 */
class Console extends Application
{
    const INPUT = 'input';
    const OUTPUT = 'output';

    public function handleInput(): Stream {}

    public function handleOutput(Stream $stream): void {}

    public function start(): void
    {
        $application = new SymfonyCli();

        try {
            $application->run();
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    public function shutdown(): void {}
}
