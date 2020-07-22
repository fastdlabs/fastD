<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\FPM;


use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Runtime;
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
     * @return Response
     */
    public function handleException(Throwable $throwable)
    {
        $class = config()->get('exception.handler');

        $handler = new $class;

        $output = json($handler->handle($throwable));

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

    public function start(): void
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
