<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Server;


use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Routing\Exceptions\RouteException;
use FastD\Routing\Exceptions\RouteNotFoundException;
use FastD\Runtime;
use Throwable;

/**
 * Class FastCGI
 * @package FastD\FPM
 */
class FastCGI extends Runtime
{
    /**
     * @return ServerRequest
     */
    public function handleInput(): ServerRequest
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

    public function handleException(Throwable $throwable): void
    {
        $this->handleOutput(json([
            'msg' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => explode(PHP_EOL, $throwable->getTraceAsString()),
        ], Response::HTTP_BAD_REQUEST));
    }

    public function run(): void
    {
        try {
            $input = $this->handleInput();
            $output = static::$application->dispatch($input);
            $this->handleOutput($output);
            unset($input, $output);
        } catch (RouteNotFoundException $e) {
            $this->handleException(new RouteException(Response::$statusTexts[Response::HTTP_FORBIDDEN], Response::HTTP_FORBIDDEN));
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }
    }
}
