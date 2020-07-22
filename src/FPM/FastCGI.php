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
        $handler = new (config()->get('exception.handler'));

        $exception = $handler->handle($throwable);
    }

    /**
     * @return Response
     */
    public function handleInput()
    {
        try {
            $input = ServerRequest::createServerRequestFromGlobals();
            static::$container->add('request', $input);
            return  static::$container->get('dispatcher')->dispatch($input);
        } catch (Throwable $exception) {
            return $this->handleException($exception);
        }
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
        $output = $this->handleInput();

        $this->handleOutput($output);
    }
}
