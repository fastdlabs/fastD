<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Runtime\FPM;


use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Runtime\Runtime;
use Monolog\Logger;
use Throwable;

/**
 * Class FastCGI
 * @package FastD\FPM
 */
class FastCGI extends Runtime
{
    public function handleLog(int $level, string $message, array $context = []): void
    {
        // TODO: Implement log() method.
    }

    /**
     * @param Throwable $throwable
     */
    public function handleException(Throwable $throwable)
    {
        $handle = config()->get('exception.handle');

        $output = json([
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
            'trace' => explode("\r\n", $throwable->getTraceAsString()),
        ]);

        $this->handleLog(Logger::ERROR, $throwable->getMessage(), [
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
            'trace' => explode("\r\n", $throwable->getTraceAsString()),
        ]);

        $this->handleOutput($output);
    }

    /**
     * @return ServerRequest
     */
    public function handleInput()
    {
        return ServerRequest::createServerRequestFromGlobals();
    }

    /**
     * @param Response $output
     * @return void
     */
    public function handleOutput($output)
    {
        $output->send();
    }

    public function run(): void
    {
        try {
            $input = $this->handleInput();
            $output = static::$container->get('dispatcher')->dispatch($input);
            $this->handleOutput($output);
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }
    }
}
